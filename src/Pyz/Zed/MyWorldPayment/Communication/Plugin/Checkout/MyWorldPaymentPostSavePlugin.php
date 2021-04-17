<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentConfirmationRequestTransfer;
use Generated\Shared\Transfer\PaymentDataRequestTransfer;
use Generated\Shared\Transfer\PaymentSessionRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class MyWorldPaymentPostSavePlugin extends AbstractPlugin implements CheckoutPostSaveHookInterface
{
    private const ERROR_QUOTE_ASSERTION = 'Error occurred during quote validation.';
    private const ERROR_PAYMENT_CONFIRMATION = 'Error occurred trying to confirm the payment: %s';
    private const ERROR_PAYMENT_DATA = 'Error occurred trying fetch payment data: %s';
    private const ERROR_PAYMENT_TRANSACTION_GENERIC = 'Generic payment transaction error';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        if (!$this->assertMyWorldPaymentApplicable($quoteTransfer)) {
            return;
        }

        if (!$this->assertQuote($quoteTransfer)) {
            $checkoutResponse->setIsSuccess(false);
            $checkoutResponse->addError($this->createCheckoutErrorTransfer(self::ERROR_QUOTE_ASSERTION));

            return;
        }

        $apiResponseTransfer = $this->confirmPayment($quoteTransfer);
        if (!$apiResponseTransfer->getIsSuccess()) {
            $this->handleUnsuccessfulRequestErrors($apiResponseTransfer, $checkoutResponse, self::ERROR_PAYMENT_CONFIRMATION);

            return;
        }

        $paymentId = $apiResponseTransfer->getPaymentConfirmationResponseTransfer()->getPaymentId();
        $apiResponseTransfer = $this->getPayment($paymentId);

        if (!$this->assertPaymentSuccessful($apiResponseTransfer)) {
            $this->handleFailedPaymentErrors($apiResponseTransfer, $checkoutResponse);
        }
    }

    /**
     * @param string $paymentId
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    private function getPayment(string $paymentId): MyWorldApiResponseTransfer
    {
        $myWorldApiRequestGetPaymentTransfer = new MyWorldApiRequestTransfer();
        $myWorldApiRequestGetPaymentTransfer->setPaymentDataRequest($this->createPaymentDataRequestTransfer($paymentId));

        return $this->getFactory()->getUtilPollingService()->executePollingProcess(
            function () use ($myWorldApiRequestGetPaymentTransfer) {
                return $this->getFacade()->getPayment($myWorldApiRequestGetPaymentTransfer);
            },
            function (MyWorldApiResponseTransfer $apiResponseTransfer): bool {
                foreach ($apiResponseTransfer->getPaymentDataResponse()->getTransactions() as $transaction) {
                    if ($transaction->getStatusCode() === MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_IN_PROGRESS) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $apiResponseTransfer
     *
     * @return bool
     */
    private function assertPaymentSuccessful(MyWorldApiResponseTransfer $apiResponseTransfer): bool
    {
        return $apiResponseTransfer->getIsSuccess()
            && $apiResponseTransfer->getPaymentDataResponse()->getStatus() === MyWorldPaymentApiConfig::PAYMENT_STATUS_CHARGED;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $apiResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    private function handleFailedPaymentErrors(
        MyWorldApiResponseTransfer $apiResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $checkoutResponseTransfer->setIsSuccess(false);
        if (!$apiResponseTransfer->getIsSuccess()) {
            $this->handleUnsuccessfulRequestErrors($apiResponseTransfer, $checkoutResponseTransfer, self::ERROR_PAYMENT_DATA);

            return;
        }

        foreach ($apiResponseTransfer->getPaymentDataResponse()->getTransactions() as $paymentTransactionTransfer) {
            $statusCode = $paymentTransactionTransfer->getStatusCode();
            if ($statusCode === MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_ACCEPTED) {
                continue;
            }

            $errorDescription = $this->getPaymentTransactionErrorDescription($statusCode);
            $errorTransfer = $this->createCheckoutErrorTransfer($errorDescription);
            $checkoutResponseTransfer->addError($errorTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $apiResponseTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    private function handleUnsuccessfulRequestErrors(
        MyWorldApiResponseTransfer $apiResponseTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $errorMessage
    ): void {
        $checkoutResponseTransfer->setIsSuccess(false);
        $errorTransfer = $this->createCheckoutErrorTransfer(
            $errorMessage,
            $apiResponseTransfer->getError()->getErrorMessage()
        );
        $checkoutResponseTransfer->addError($errorTransfer);
    }

    /**
     * @param int $statusCode
     *
     * @return string
     */
    private function getPaymentTransactionErrorDescription(int $statusCode): string
    {
        return MyWorldPaymentConfig::PAYMENT_TRANSACTION_ERRORS_DESCRIPTION_MAP[$statusCode] ?? self::ERROR_PAYMENT_TRANSACTION_GENERIC;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    private function confirmPayment(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        $apiRequestTransfer = new MyWorldApiRequestTransfer();
        $apiRequestTransfer->setPaymentConfirmationRequest($this->createPaymentConfirmationTransfer($quoteTransfer));
        $apiRequestTransfer->setPaymentSessionRequest($this->createPaymentSessionRequestTransfer($quoteTransfer));

        return $this->getFacade()->confirmPayment($apiRequestTransfer);
    }

    /**
     * @param string $paymentId
     *
     * @return \Generated\Shared\Transfer\PaymentDataRequestTransfer
     */
    private function createPaymentDataRequestTransfer(string $paymentId): PaymentDataRequestTransfer
    {
        return (new PaymentDataRequestTransfer())->setPaymentId($paymentId);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentSessionRequestTransfer
     */
    private function createPaymentSessionRequestTransfer(QuoteTransfer $quoteTransfer): PaymentSessionRequestTransfer
    {
        return (new PaymentSessionRequestTransfer())
            ->setSsoAccessToken($quoteTransfer->getCustomer()->getSsoAccessToken());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentConfirmationRequestTransfer
     */
    private function createPaymentConfirmationTransfer(QuoteTransfer $quoteTransfer): PaymentConfirmationRequestTransfer
    {
        return (new PaymentConfirmationRequestTransfer())
            ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId())
            ->setConfirmationCode($quoteTransfer->getSmsCode());
    }

    /**
     * @param string $message
     * @param string ...$parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    private function createCheckoutErrorTransfer(string $message, string ...$parameters): CheckoutErrorTransfer
    {
        $formatterMessage = sprintf($message, ...$parameters);

        return (new CheckoutErrorTransfer())->setMessage($formatterMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    private function assertQuote(QuoteTransfer $quoteTransfer): bool
    {
        try {
            $quoteTransfer->requireMyWorldPaymentSessionId();
            $quoteTransfer->requireCustomer();
            $quoteTransfer->getCustomer()->requireSsoAccessToken();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    private function assertMyWorldPaymentApplicable(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === $this->getConfig()->getMyWorldPaymentProviderKey()) {
                return true;
            }
        }

        return false;
    }
}
