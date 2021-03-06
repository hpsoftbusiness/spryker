<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen\Form\Validation;

use Pyz\Client\MyWorldPayment\MyWorldPaymentClient;
use Pyz\Yves\Adyen\AdyenConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

class CreditCardValidationGroupResolver
{
    public const PRICE_TO_PAY_COVERED_BY_INTERNAL_PAYMENT_GROUP = 'PRICE_TO_PAY_COVERED_BY_INTERNAL_PAYMENT_GROUP';
    public const NO_VALIDATE = 'NO_VALIDATE';

    /**
     * @var \Pyz\Yves\Adyen\AdyenConfig
     */
    private $config;

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentClient
     */
    private $myWorldPaymentClient;

    /**
     * @param \Pyz\Yves\Adyen\AdyenConfig $config
     * @param \Pyz\Client\MyWorldPayment\MyWorldPaymentClient $myWorldPaymentClient
     */
    public function __construct(
        AdyenConfig $config,
        MyWorldPaymentClient $myWorldPaymentClient
    ) {
        $this->config = $config;
        $this->myWorldPaymentClient = $myWorldPaymentClient;
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
        $quoteTransfer = $form->getParent()->getData();

        if (!$quoteTransfer
            || $quoteTransfer->getPayment()->getPaymentSelection() !== $this->config->getAdyenCreditCardName()
        ) {
            return [self::NO_VALIDATE];
        }

        $selectedInternalPaymentOptionId = $this->myWorldPaymentClient->findUsedInternalPaymentMethodOptionId($quoteTransfer);

        if ($selectedInternalPaymentOptionId === null
            || !$this->myWorldPaymentClient->assertInternalPaymentCoversPriceToPay($quoteTransfer, $selectedInternalPaymentOptionId)
        ) {
            return [Constraint::DEFAULT_GROUP];
        }

        return [self::PRICE_TO_PAY_COVERED_BY_INTERNAL_PAYMENT_GROUP];
    }
}
