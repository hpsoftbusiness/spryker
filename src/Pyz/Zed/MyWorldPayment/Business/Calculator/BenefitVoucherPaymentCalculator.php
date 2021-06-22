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
use Pyz\Service\Customer\CustomerServiceInterface;
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
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct(
        MyWorldPaymentConfig $myWorldPaymentConfig,
        CustomerServiceInterface $customerService
    ) {
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
        $this->customerService = $customerService;
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
            $calculableObjectTransfer = $this->calculateBenefitVouchersAmount($calculableObjectTransfer);
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
            if (!$itemTransfer->getUseBenefitVoucher() || !$this->assertBenefitVoucherDealData($itemTransfer)) {
                continue;
            }

            $benefitVoucherData = $itemTransfer->getBenefitVoucherDealData();
            $totalItemBenefitVoucherDiscountAmount = (int)($benefitVoucherData->getAmount() * $itemTransfer->getQuantity());
            $itemTransfer->setTotalUsedBenefitVouchersAmount($totalItemBenefitVoucherDiscountAmount);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    private function calculateBenefitVouchersAmount(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $totalBenefitVouchersDiscountAmount = 0;
        $customerTransfer = $calculableObjectTransfer->getOriginalQuote()->getCustomer();
        $availableAmountOfBenefitVoucher = $this->customerService->getCustomerBenefitVoucherBalanceAmount($customerTransfer);

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$this->assertBenefitVoucherDealData($itemTransfer)) {
                $itemTransfer->setUseBenefitVoucher(false);

                continue;
            }

            if ($itemTransfer->getUseBenefitVoucher()) {
                $benefitVoucherData = $itemTransfer->getBenefitVoucherDealData();
                $totalItemBenefitVoucherDiscountAmount = (int)($benefitVoucherData->getAmount() * $itemTransfer->getQuantity());
                if ($totalBenefitVouchersDiscountAmount + $totalItemBenefitVoucherDiscountAmount > $availableAmountOfBenefitVoucher) {
                    $this->clearItemBenefitVoucherSelection($itemTransfer);

                    continue;
                }

                $itemTransfer->setTotalUsedBenefitVouchersAmount($totalItemBenefitVoucherDiscountAmount);
                $totalBenefitVouchersDiscountAmount += $totalItemBenefitVoucherDiscountAmount;
            } else {
                $this->clearItemBenefitVoucherSelection($itemTransfer);
            }
        }

        $calculableObjectTransfer->setTotalUsedBenefitVouchersAmount($totalBenefitVouchersDiscountAmount);

        if ($totalBenefitVouchersDiscountAmount > 0) {
            $payment = $this->createPaymentMethod($totalBenefitVouchersDiscountAmount);

            $calculableObjectTransfer->addPayment($payment);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function clearItemBenefitVoucherSelection(ItemTransfer $itemTransfer): void
    {
        $itemTransfer->setUseBenefitVoucher(false);
        $itemTransfer->setTotalUsedBenefitVouchersAmount(0);
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
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    private function isBenefitVoucherUseSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
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
    private function assertBenefitVoucherDealData(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireBenefitVoucherDealData();
            $itemTransfer->getBenefitVoucherDealData()->requireIsStore();

            return $itemTransfer->getBenefitVoucherDealData()->getIsStore();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }
}
