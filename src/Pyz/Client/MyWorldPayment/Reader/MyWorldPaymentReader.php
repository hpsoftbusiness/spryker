<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Client\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Service\Customer\CustomerServiceInterface;

class MyWorldPaymentReader implements MyWorldPaymentReaderInterface
{
    /**
     * @var \Pyz\Client\Customer\CustomerClientInterface
     */
    private $customerClient;

    /**
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentConfig
     */
    private $config;

    /**
     * @param \Pyz\Client\Customer\CustomerClientInterface $customerClient
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     * @param \Pyz\Client\MyWorldPayment\MyWorldPaymentConfig $config
     */
    public function __construct(CustomerClientInterface $customerClient, CustomerServiceInterface $customerService, MyWorldPaymentConfig $config)
    {
        $this->customerClient = $customerClient;
        $this->customerService = $customerService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $paymentOptionId
     *
     * @return bool
     */
    public function assertInternalPaymentCoversPriceToPay(QuoteTransfer $quoteTransfer, int $paymentOptionId): bool
    {
        $ePayments = [
            $this->config->getPaymentNameEVoucher(),
            $this->config->getPaymentNameEVoucherOnBehalfOfMarketer(),
            $this->config->getPaymentNameCashback(),
        ];

        $customerTransfer = $quoteTransfer->getCustomer();

        if (!$customerTransfer) {
            $customerTransfer = $this->customerClient->getCustomer();
        }

        $customerBalance = $this->customerService->getCustomerBalanceAmountByPaymentOptionId($customerTransfer, $paymentOptionId);
        $priceToPay = $quoteTransfer->getTotals()->getPriceToPay();

        /** @var \Generated\Shared\Transfer\PaymentTransfer $payment */
        foreach ($quoteTransfer->getPayments() as $payment) {
            if (in_array($payment->getPaymentMethodName(), $ePayments)) {
                $priceToPay += $payment->getAmount();
            }
        }

        return $customerBalance >= $priceToPay;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    public function findUsedInternalPaymentMethodOptionId(QuoteTransfer $quoteTransfer): ?int
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
