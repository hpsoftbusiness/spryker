<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class EVoucherPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    private const DEFAULT_COMMON_PRICE = 0;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    protected $marketplaceApiClient;

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    protected $myWorldPaymentConfig;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $marketplaceApiClient
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     */
    public function __construct(MyWorldMarketplaceApiClientInterface $marketplaceApiClient, MyWorldPaymentConfig $myWorldPaymentConfig)
    {
        $this->marketplaceApiClient = $marketplaceApiClient;
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $calculableObjectTransfer = $this->removeEVoucherPayment($calculableObjectTransfer);

        if ($this->isEVoucherPaymentSelected($calculableObjectTransfer)) {
            $calculableObjectTransfer = $this->addPaymentToQuote($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        if ($this->isEVoucherPaymentSelected($calculableObjectTransfer) && (bool)$calculableObjectTransfer->getMyWorldPaymentSessionId()) {
            $eVoucherPayment = $this->getEVoucherPayment($calculableObjectTransfer);

            if ($eVoucherPayment) {
                $calculableObjectTransfer
                    ->getTotals()
                    ->setDiscountTotal($eVoucherPayment->getAmount());
            }
        } else {
            $calculableObjectTransfer = $this->removeEVoucherPayment($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function addPaymentToQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $clientInformationResponseTransfer = $this->getCustomerInformation(
            $calculableObjectTransfer->getOriginalQuote()->getCustomer()
        );

        if ($clientInformationResponseTransfer->getCustomerBalance()->getAvailableCashbackAmount()->toFloat() === 0.00) {
            return $calculableObjectTransfer;
        }

        $availableVouchers = $clientInformationResponseTransfer->getCustomerBalance()->getAvailableCashbackAmount()->toInt() * 100;
        $commonPrice = $this->getCommonAmountOfVouchersFromQuote($calculableObjectTransfer);

        $calculableObjectTransfer->addPayment(
            $this->createEVouchersPaymentTransfer(
                $availableVouchers,
                $commonPrice > $availableVouchers ? $availableVouchers : $commonPrice
            )
        );

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function getCommonAmountOfVouchersFromQuote(CalculableObjectTransfer $calculableObjectTransfer): int
    {
        return array_reduce($calculableObjectTransfer->getItems()->getArrayCopy(), function (int $carry, ItemTransfer $itemTransfer) {
            if ($itemTransfer->getSumPrice()) {
                $carry += $itemTransfer->getSumPrice();
            }

            return $carry;
        }, static::DEFAULT_COMMON_PRICE);
    }

    /**
     * @param int $availablePrice
     * @param int $chargePrice
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createEVouchersPaymentTransfer(int $availablePrice, int $chargePrice): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setPaymentProvider($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setPaymentSelection($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setPaymentMethod($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setPaymentMethodName($this->myWorldPaymentConfig->getOptionEVoucherName())
            ->setAvailableAmount($availablePrice)
            ->setAmount($chargePrice)
            ->setIsLimitedAmount(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isEVoucherPaymentSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        return (bool)$calculableObjectTransfer->getMyWorldUseEVoucherBalance();
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isEVoucherAddedAlready(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        foreach ($calculableObjectTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentSelection() === $this->myWorldPaymentConfig->getOptionEVoucherName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function getEVoucherPayment(CalculableObjectTransfer $calculableObjectTransfer): ?PaymentTransfer
    {
        foreach ($calculableObjectTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentSelection() === $this->myWorldPaymentConfig->getOptionEVoucherName()) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function removeEVoucherPayment(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($calculableObjectTransfer->getPayments() as $index => $paymentTransfer) {
            if ($paymentTransfer->getPaymentSelection() === $this->myWorldPaymentConfig->getOptionEVoucherName()) {
                unset($calculableObjectTransfer->getPayments()[$index]);
            }
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerInformation(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->marketplaceApiClient->getCustomerInformationByCustomerNumberOrId($customerTransfer);
    }
}
