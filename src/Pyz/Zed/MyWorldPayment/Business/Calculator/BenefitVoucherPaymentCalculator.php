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
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function reduceItemsPrices(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $commonBenefitVouchersUsed = 0;
        $commonReducedPrice = 0;

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher() && $this->assertBenefitVoucherSalesPrice($itemTransfer)) {
                $benefitSalesPrice = $itemTransfer->getBenefitVoucherDealData()->getSalesPrice();
                $benefitAmount = $itemTransfer->getBenefitVoucherDealData()->getAmount();

                $newPriceForItemsWithBenefitVouchers = (int)((100 * $benefitSalesPrice) * $itemTransfer->getQuantity());

                $oldPriceForItemsWithBenefitVouchers = $itemTransfer->getUnitPrice() * $itemTransfer->getQuantity();

                $itemTransfer->setTotalUsedBenefitVouchersAmount(
                    $benefitAmount * $itemTransfer->getQuantity()
                );

                $commonBenefitVouchersUsed += $itemTransfer->getTotalUsedBenefitVouchersAmount();
                $commonReducedPrice += $oldPriceForItemsWithBenefitVouchers - $newPriceForItemsWithBenefitVouchers;
            }
        }

        $calculableObjectTransfer->setTotalUsedBenefitVouchersAmount($commonBenefitVouchersUsed);

        if ($commonReducedPrice > 0) {
            $payment = $this->createPaymentMethod($commonReducedPrice);

            $calculableObjectTransfer->addPayment($payment);
        }

        return $calculableObjectTransfer;
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

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
