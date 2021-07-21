<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{
    protected const KEY_MESSAGE_SMS_CODE_SEND_ERROR = 'checkout.step.summary.sms_code_send_error';

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface
     */
    private $myWorldPaymentClient;

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    private $flashMessenger;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @param \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface $myWorldPaymentClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        MyWorldPaymentClientInterface $myWorldPaymentClient,
        FlashMessengerInterface $flashMessenger,
        TranslatorInterface $translator
    ) {
        $this->myWorldPaymentClient = $myWorldPaymentClient;
        $this->flashMessenger = $flashMessenger;
        $this->translator = $translator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        $quoteTransfer->setSmsCode(null);

        /**
         * TODO: general logic of sms confirmation should be fixed:
         * - moved to proper place - checker shouldn't update the state
         * - remove usage of global variables
         */
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== Request::METHOD_POST &&
            $this->wasPaymentSessionCreatedSuccessfully($quoteTransfer) &&
            $quoteTransfer->getMyWorldPaymentIsSmsAuthenticationRequired()
        ) {
            $myWorldApiRequestTransfer = new MyWorldApiRequestTransfer();
            $myWorldApiRequestTransfer->setPaymentCodeGenerateRequest(
                (new PaymentCodeGenerateRequestTransfer())
                    ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId())
            );

            $myWorldApiResponseTransfer = $this->myWorldPaymentClient->sendSmsCodeToCustomer(
                $myWorldApiRequestTransfer
            );

            if (!$myWorldApiResponseTransfer->getIsSuccess()) {
                $myWorldApiResponseTransfer->requireError();

                if ($myWorldApiResponseTransfer->getError()->getErrorCode(
                ) === MyWorldPaymentConstants::RESPONSE_ERROR_CODE_GENERATION_NOT_CONFIGURED) {
                    return true;
                } else {
                    $this->flashMessenger->addErrorMessage(
                        $this->translator->trans(static::KEY_MESSAGE_SMS_CODE_SEND_ERROR)
                    );
                }
            }

            return $myWorldApiResponseTransfer->getIsSuccess();
        }

        return $this->wasPaymentSessionCreatedSuccessfully($quoteTransfer) || !$this->isMyWorldPaymentUsed(
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function wasPaymentSessionCreatedSuccessfully(QuoteTransfer $quoteTransfer): bool
    {
        return $this->isMyWorldPaymentUsed($quoteTransfer)
            && $quoteTransfer->getMyWorldPaymentSessionId();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isMyWorldPaymentUsed(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getPayments() as $payment) {
            if ($payment->getPaymentProvider() === MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                return true;
            }
        }

        return false;
    }
}
