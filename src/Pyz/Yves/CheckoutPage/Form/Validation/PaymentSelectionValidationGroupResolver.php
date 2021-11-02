<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Validation;

use Pyz\Client\Messenger\MessengerClientInterface;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClient;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

class PaymentSelectionValidationGroupResolver
{
    public const PRICE_TO_PAY_COVERED_BY_INTERNAL_PAYMENT_GROUP = 'PRICE_TO_PAY_COVERED_BY_INTERNAL_PAYMENT_GROUP';
    public const FLASH_GROUP = 'FLASH_GROUP';

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentClient
     */
    private $myWorldPaymentClient;

    /**
     * @var \Pyz\Client\Messenger\MessengerClientInterface
     */
    private $flashMessenger;

    /**
     * @param \Pyz\Client\MyWorldPayment\MyWorldPaymentClient $myWorldPaymentClient
     * @param \Pyz\Client\Messenger\MessengerClientInterface $flashMessenger
     */
    public function __construct(MyWorldPaymentClient $myWorldPaymentClient, MessengerClientInterface $flashMessenger)
    {
        $this->myWorldPaymentClient = $myWorldPaymentClient;
        $this->flashMessenger = $flashMessenger;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return string[]
     */
    public function __invoke(FormInterface $form): array
    {
        /**
         * @var \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
         */
        $quoteTransfer = $form->getData();

        if (!$quoteTransfer) {
            return [Constraint::DEFAULT_GROUP];
        }

        $selectedInternalPaymentOptionId = $this->myWorldPaymentClient->findUsedInternalPaymentMethodOptionId($quoteTransfer);

        if ($selectedInternalPaymentOptionId !== null
            && !$quoteTransfer->getPayment()->getPaymentSelection()
            && !$this->myWorldPaymentClient->assertInternalPaymentCoversPriceToPay($quoteTransfer, $selectedInternalPaymentOptionId)
        ) {
            $this->flashMessenger->addErrorMessage('payment.payment_method.error');

            return [self::FLASH_GROUP];
        }

        if ($selectedInternalPaymentOptionId === null
            || $quoteTransfer->getPayment()->getPaymentSelection()
        ) {
            return [Constraint::DEFAULT_GROUP];
        }

        return [self::PRICE_TO_PAY_COVERED_BY_INTERNAL_PAYMENT_GROUP];
    }
}
