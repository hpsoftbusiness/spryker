<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\ConfirmPaymentStep;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentConfirmationRequestTransfer;
use Generated\Shared\Transfer\PaymentDataRequestTransfer;
use Generated\Shared\Transfer\PaymentSessionRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{
    protected const MESSAGE_CONFIRM_PAYMENT_ERROR = 'checkout.step.confirm_payment.error';

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
        if ($quoteTransfer->getSmsCode() && $quoteTransfer->getMyWorldPaymentSessionId()) {
            $myWorldApiRequestTransfer = new MyWorldApiRequestTransfer();
            $myWorldApiRequestTransfer->setPaymentConfirmationRequest(
                (new PaymentConfirmationRequestTransfer())
                ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId())
                ->setConfirmationCode($quoteTransfer->getSmsCode())
            );
            $myWorldApiRequestTransfer->setPaymentSessionRequest(
                (new PaymentSessionRequestTransfer())
                    ->setSsoAccessToken($quoteTransfer->getCustomer()->getSsoAccessToken())
            );

            $myWorldApiResponseTransfer = $this->myWorldPaymentClient->confirmPayment($myWorldApiRequestTransfer);

            $quoteTransfer->setSmsCode(null);

            if (!$myWorldApiResponseTransfer->getIsSuccess()) {
                $this->flashMessenger->addErrorMessage(
                    $this->translator->trans(static::MESSAGE_CONFIRM_PAYMENT_ERROR)
                );
            } else {
                ($myWorldApiRequestGetPaymentTransfer = new MyWorldApiRequestTransfer())
                    ->setPaymentDataRequest(
                        (new PaymentDataRequestTransfer())
                            ->setPaymentId($myWorldApiResponseTransfer->getPaymentConfirmationResponseTransfer()->getPaymentId())
                    );

                $payment = $this->myWorldPaymentClient->getPayment($myWorldApiRequestGetPaymentTransfer);

                if (!$payment->getIsSuccess()) {
                    $this->flashMessenger->addErrorMessage(
                        $this->translator->trans(static::MESSAGE_CONFIRM_PAYMENT_ERROR)
                    );
                }

                return $payment->getIsSuccess();
                // TODO: Create the functionality to make sure that the confirmation of the payment session is passed
            }

            return $myWorldApiResponseTransfer->getIsSuccess();
        }

        return true;
    }
}
