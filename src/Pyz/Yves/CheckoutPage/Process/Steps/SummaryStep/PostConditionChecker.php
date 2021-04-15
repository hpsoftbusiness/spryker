<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostConditionChecker implements PostConditionCheckerInterface
{
    protected const KEY_MESSAGE_SMS_CODE_INPUT_ERROR = 'checkout.step.summary.sms_code_input_error';

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
        if ($quoteTransfer->getMyWorldPaymentIsSmsAuthenticationRequired()) {
            $myWorldApiRequestTransfer = new MyWorldApiRequestTransfer();
            $myWorldApiRequestTransfer->setPaymentCodeValidateRequest(
                (new PaymentCodeValidateRequestTransfer())
                    ->setSessionId($quoteTransfer->getMyWorldPaymentSessionId())
                    ->setConfirmationCode($quoteTransfer->getSmsCode())
            );
            $myWorldApiRequestTransfer->setPaymentSessionRequest(
                (new PaymentSessionRequestTransfer())
                    ->setSsoAccessToken($quoteTransfer->getCustomer()->getSsoAccessToken())
            );
            $myWorldApiResponseTransfer = $this->myWorldPaymentClient->validateSmsCode($myWorldApiRequestTransfer);
            if (!$myWorldApiResponseTransfer->getPaymentCodeValidateResponse()->getIsValid()) {
                $this->flashMessenger->addErrorMessage(
                    $this->translator->trans(static::KEY_MESSAGE_SMS_CODE_INPUT_ERROR)
                );

                return false;
            }
        }

        return true;
    }
}
