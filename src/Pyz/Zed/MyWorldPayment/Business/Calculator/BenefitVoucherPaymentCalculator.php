<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class BenefitVoucherPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    protected const DEFAULT_AMOUNT_OF_ITEMS = 1;

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
    public function __construct(
        MyWorldMarketplaceApiClientInterface $marketplaceApiClient,
        MyWorldPaymentConfig $myWorldPaymentConfig
    ) {
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
        $calculableObjectTransfer = $this->removePaymentMethod($calculableObjectTransfer);

        if ($this->isBenefitVoucherUseSelected($calculableObjectTransfer)) {
            $calculableObjectTransfer = $this->reduceItemsPrices($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function reduceItemsPrices(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $commonReducedPrice = 0;
        $availableAmountOfBenefitVoucher = $calculableObjectTransfer->getOriginalQuote()->getCustomer()->getCustomerBalance()->getAvailableBenefitVoucherAmount();

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher() && $this->assertBenefitVoucherSalesPrice($itemTransfer)) {
                $benefitSalesPrice = $itemTransfer->getBenefitVoucherDealData()->getSalesPrice();

                $newPriceForItemsWithBenefitVouchers = (int)($benefitSalesPrice * $itemTransfer->getQuantity());

                $oldPriceForItemsWithBenefitVouchers = $itemTransfer->getUnitPrice() * $itemTransfer->getQuantity();

                $this->calculateItemUsedBenefitVouchers($itemTransfer, $availableAmountOfBenefitVoucher->toInt());

                $commonReducedPrice += $oldPriceForItemsWithBenefitVouchers - $newPriceForItemsWithBenefitVouchers;
            } elseif (
                !$itemTransfer->getUseBenefitVoucher()
                && $this->assertBenefitVoucherSalesPrice($itemTransfer)
            ) {
                $itemTransfer->setTotalUsedBenefitVouchersAmount(0);

                $itemTransfer->setUnitGrossPrice($itemTransfer->getOriginUnitGrossPrice());
                $itemTransfer->setSumGrossPrice($itemTransfer->getOriginUnitGrossPrice() * $itemTransfer->getQuantity());
            }
        }

        $calculableObjectTransfer->setTotalUsedBenefitVouchersAmount(
            $this->getCommonUsedBenefitItems($calculableObjectTransfer)
        );

        if ($commonReducedPrice > 0) {
            $payment = $this->createPaymentMethod($commonReducedPrice);

            $calculableObjectTransfer->addPayment($payment);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function getCommonUsedBenefitItems(CalculableObjectTransfer $calculableObjectTransfer): int
    {
        return array_reduce(
            $calculableObjectTransfer->getItems()->getArrayCopy(),
            function (int $carry, ItemTransfer $itemTransfer) {
                $carry += $itemTransfer->getTotalUsedBenefitVouchersAmount();

                return $carry;
            },
            0
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $availablePoints
     *
     * @return void
     */
    protected function calculateItemUsedBenefitVouchers(ItemTransfer $itemTransfer, int $availablePoints): void
    {
        if (!$this->assertBenefitVoucherSalesPrice($itemTransfer)) {
            return;
        }

        $benefitVoucherDealData = $itemTransfer->getBenefitVoucherDealData();

        $totalNeededBenefitVouchers = $itemTransfer->getQuantity() * $benefitVoucherDealData->getAmount();
        if ($totalNeededBenefitVouchers > $availablePoints) {
            return;
        }

        $itemTransfer->setTotalUsedBenefitVouchersAmount($totalNeededBenefitVouchers);
        $itemTransfer->setUnitGrossPrice($benefitVoucherDealData->getSalesPrice());
        $itemTransfer->setSumGrossPrice($benefitVoucherDealData->getSalesPrice() * $itemTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function removePaymentMethod(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $newList = new ArrayObject();

        foreach ($calculableObjectTransfer->getPayments() as $payment) {
            if ($payment->getPaymentSelection() !== $this->myWorldPaymentConfig->getOptionBenefitVoucherName()) {
                $newList->append($payment);
            }
        }

        $calculableObjectTransfer->setPayments($newList);

        return $calculableObjectTransfer;
    }

    /**
     * @param int $amountOfCharged
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentMethod(int $amountOfCharged): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setAmount($amountOfCharged)
            ->setIsLimitedAmount(true)
            ->setAvailableAmount($amountOfCharged)
            ->setPaymentProvider(SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD)
            ->setPaymentMethodName($this->myWorldPaymentConfig->getOptionBenefitVoucherName())
            ->setPaymentMethod($this->myWorldPaymentConfig->getOptionBenefitVoucherName())
            ->setPaymentSelection($this->myWorldPaymentConfig->getOptionBenefitVoucherName());
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isBenefitVoucherUseSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertBenefitVoucherSalesPrice(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireBenefitVoucherDealData();
            $itemTransfer->getBenefitVoucherDealData()->requireIsStore();

            return $itemTransfer->getBenefitVoucherDealData()->getIsStore();
        } catch (Exception $exception) {
            return false;
        }
    }
}
