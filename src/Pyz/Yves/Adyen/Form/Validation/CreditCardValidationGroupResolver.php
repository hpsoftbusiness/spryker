<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen\Form\Validation;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Yves\Adyen\AdyenConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

class CreditCardValidationGroupResolver
{
    public const GRAND_TOTAL_COVERED_BY_INTERNAL_PAYMENT_GROUP = 'GRAND_TOTAL_COVERED_BY_INTERNAL_PAYMENT_GROUP';

    /**
     * @var \Pyz\Yves\Adyen\AdyenConfig
     */
    private $config;

    /**
     * @var \Pyz\Client\Customer\CustomerClientInterface
     */
    private $customerClient;

    /**
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @param \Pyz\Yves\Adyen\AdyenConfig $config
     * @param \Pyz\Client\Customer\CustomerClientInterface $customerClient
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct(
        AdyenConfig $config,
        CustomerClientInterface $customerClient,
        CustomerServiceInterface $customerService
    ) {
        $this->config = $config;
        $this->customerClient = $customerClient;
        $this->customerService = $customerService;
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

        if (!$quoteTransfer) {
            return [Constraint::DEFAULT_GROUP];
        }

        $selectedInternalPaymentOptionId = $this->findUsedInternalPaymentMethodOptionId($quoteTransfer);
        if ($selectedInternalPaymentOptionId === null
            || !$this->assertInternalPaymentCoversGrantTotalAmount($quoteTransfer, $selectedInternalPaymentOptionId)) {
            return [Constraint::DEFAULT_GROUP];
        }

        return [self::GRAND_TOTAL_COVERED_BY_INTERNAL_PAYMENT_GROUP];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $paymentOptionId
     *
     * @return bool
     */
    private function assertInternalPaymentCoversGrantTotalAmount(QuoteTransfer $quoteTransfer, int $paymentOptionId): bool
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        if (!$customerTransfer) {
            $customerTransfer = $this->customerClient->getCustomer();
        }

        $customerBalance = $this->customerService->getCustomerBalanceAmountByPaymentOptionId($customerTransfer, $paymentOptionId);
        $grandTotalAmount = $quoteTransfer->getTotals()->getGrandTotal();

        return $customerBalance >= $grandTotalAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    private function findUsedInternalPaymentMethodOptionId(QuoteTransfer $quoteTransfer): ?int
    {
        if ($quoteTransfer->getUseEVoucherBalance()) {
            return $this->config->getPaymentOptionIdEVoucher();
        }

        if ($quoteTransfer->getUseEVoucherOnBehalfOfMarketer()) {
            return $this->config->getPaymentOptionIdEVoucherOnBehalfOfMarketer();
        }

        if ($quoteTransfer->getUseCashbackBalance()) {
            return $this->config->getPaymentOptionIdCashback();
        }

        return null;
    }
}
