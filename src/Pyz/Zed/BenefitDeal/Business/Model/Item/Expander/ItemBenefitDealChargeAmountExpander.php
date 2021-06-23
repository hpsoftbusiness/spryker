<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Model\Item\Expander;

use Generated\Shared\Transfer\BenefitDealChargeAmountDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ItemBenefitDealChargeAmountExpander implements ItemBenefitDealChargeAmountExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function expandItems(iterable $itemTransfers): void
    {
        foreach ($itemTransfers as $item) {
            $amountDataTransfer = $item->getBenefitDealChargeAmountData();

            if ($this->isBenefitVoucherDataProvided($item)) {
                $amountDataTransfer = $this->createBenefitVouchersChangeAmountToTransfer($item);
            } elseif ($this->isShoppingPointsDataProvided($item)) {
                $amountDataTransfer = $this->createShoppingPointsChargeAmountToTransfer($item);
            }

            $item->setBenefitDealChargeAmountData($amountDataTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\BenefitDealChargeAmountDataTransfer
     */
    private function createBenefitVouchersChangeAmountToTransfer(ItemTransfer $itemTransfer): BenefitDealChargeAmountDataTransfer
    {
        return ($amountDataTransfer = new BenefitDealChargeAmountDataTransfer())
            ->setUnitBenefitVouchersAmount($itemTransfer->getBenefitVoucherDealData()->getAmount())
            ->setUnitSalesPriceAmount(
                $itemTransfer->getBenefitVoucherDealData()->getSalesPrice()
            )
            ->setTotalSalesPriceAmount(
                $amountDataTransfer->getUnitSalesPriceAmount() * $itemTransfer->getQuantity()
            )
            ->setTotalBenefitVouchersAmount(
                $amountDataTransfer->getUnitBenefitVouchersAmount() * $itemTransfer->getQuantity()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\BenefitDealChargeAmountDataTransfer
     */
    private function createShoppingPointsChargeAmountToTransfer(ItemTransfer $itemTransfer): BenefitDealChargeAmountDataTransfer
    {
        return ($amountDataTransfer = new BenefitDealChargeAmountDataTransfer())
            ->setUnitShoppingPointsAmount($itemTransfer->getShoppingPointsDeal()->getShoppingPointsQuantity())
            ->setUnitSalesPriceAmount(
                $itemTransfer->getShoppingPointsDeal()->getPrice()
            )
            ->setTotalSalesPriceAmount(
                $amountDataTransfer->getUnitSalesPriceAmount() * $itemTransfer->getQuantity()
            )
            ->setTotalShoppingPointsAmount(
                $amountDataTransfer->getUnitShoppingPointsAmount() * $itemTransfer->getQuantity()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function isBenefitVoucherDataProvided(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireBenefitVoucherDealData();
            $itemTransfer->getBenefitVoucherDealData()->requireAmount();

            return $itemTransfer->getBenefitVoucherDealData()->getIsStore();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function isShoppingPointsDataProvided(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireShoppingPointsDeal();
            $itemTransfer->getShoppingPointsDeal()->requirePrice();

            return $itemTransfer->getShoppingPointsDeal()->getIsActive();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }
}
