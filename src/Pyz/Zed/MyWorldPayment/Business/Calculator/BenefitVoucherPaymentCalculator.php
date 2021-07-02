<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class BenefitVoucherPaymentCalculator implements
    MyWorldPaymentQuoteCalculatorInterface,
    MyWorldPaymentOrderCalculatorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    private $myWorldPaymentConfig;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     */
    public function __construct(
        MyWorldPaymentConfig $myWorldPaymentConfig
    ) {
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
        $this->clearAppliedBenefitVouchers($calculableObjectTransfer);

        if (!$calculableObjectTransfer->getUseBenefitVoucher()
            || !$calculableObjectTransfer->getTotalUsedBenefitVouchersAmount()) {
            $calculableObjectTransfer->setTotalUsedBenefitVouchersAmount(null);

            return $calculableObjectTransfer;
        }

        $this->calculateUsedBenefitVoucherAmountForItems($calculableObjectTransfer);
        $this->recalculateTotalUsedBenefitVoucherAmount($calculableObjectTransfer);

        if ($calculableObjectTransfer->getTotalUsedBenefitVouchersAmount() > 0) {
            $payment = $this->createPaymentMethod($calculableObjectTransfer->getTotalUsedBenefitVouchersAmount());

            $calculableObjectTransfer->addPayment($payment);
        } else {
            $calculableObjectTransfer->setUseBenefitVoucher(false);
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
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$this->assertBenefitVoucherDealApplied($itemTransfer)) {
                continue;
            }

            $this->setUnitBenefitPrice($itemTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    private function calculateUsedBenefitVoucherAmountForItems(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $totalApplicableBenefitAmount = $calculableObjectTransfer->getTotalUsedBenefitVouchersAmount();
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if ($totalApplicableBenefitAmount === 0) {
                break;
            }

            if (!$this->assertItemBenefitVoucherApplicable($itemTransfer)) {
                continue;
            }

            $itemApplicableBenefitAmount = $itemTransfer->getBenefitVoucherDealData()->getAmount() * $itemTransfer->getQuantity();
            $amountToApply = min($totalApplicableBenefitAmount, $itemApplicableBenefitAmount);
            if ($amountToApply > 0) {
                $itemTransfer->setTotalUsedBenefitVouchersAmount($amountToApply);
                $itemTransfer->setUseBenefitVoucher(true);
                $itemTransfer->setSumBenefitPrice($itemTransfer->getSumGrossPrice() - $amountToApply);
            }
            $totalApplicableBenefitAmount -= $amountToApply;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    private function recalculateTotalUsedBenefitVoucherAmount(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $totalAppliedAmount = 0;
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $totalAppliedAmount += (int)$itemTransfer->getTotalUsedBenefitVouchersAmount();
        }

        $calculableObjectTransfer->setTotalUsedBenefitVouchersAmount($totalAppliedAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    private function clearAppliedBenefitVouchers(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $this->clearItemBenefitVoucherSelection($itemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function setUnitBenefitPrice(ItemTransfer $itemTransfer): void
    {
        $unitBenefitAmount = (int)($itemTransfer->getTotalUsedBenefitVouchersAmount() / $itemTransfer->getQuantity());
        $unitBenefitPrice = $itemTransfer->getUnitGrossPrice() - $unitBenefitAmount;
        $itemTransfer->setUnitBenefitPrice($unitBenefitPrice);
        $itemTransfer->setSumBenefitPrice($unitBenefitPrice * $itemTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function clearItemBenefitVoucherSelection(ItemTransfer $itemTransfer): void
    {
        $itemTransfer->setUseBenefitVoucher(false);
        $itemTransfer->setTotalUsedBenefitVouchersAmount(null);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    private function removePaymentMethod(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
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
    private function createPaymentMethod(int $amountOfCharged): PaymentTransfer
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertBenefitVoucherDealApplied(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireUseBenefitVoucher();
            $itemTransfer->requireTotalUsedBenefitVouchersAmount();

            return $itemTransfer->getUseBenefitVoucher();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertItemBenefitVoucherApplicable(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireBenefitVoucherDealData();
            $itemTransfer->getBenefitVoucherDealData()->requireSalesPrice();
            $itemTransfer->getBenefitVoucherDealData()->requireAmount();
            $itemTransfer->getBenefitVoucherDealData()->requireIsStore();

            return $itemTransfer->getBenefitVoucherDealData()->getIsStore();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }
}
