<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Communication\Plugin\ItemEntityExpander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemEntityExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface getFacade()
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 */
class BenefitVoucherItemEntityExpanderPlugin extends AbstractPlugin implements ItemEntityExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return void
     */
    public function expand(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): void {
        if (!$this->assertBenefitDealsIsApplicable($itemTransfer)) {
            return;
        }

        $orderSalesItemBenefitDealEntity = new PyzSalesOrderItemBenefitDealEntityTransfer();
        $orderSalesItemBenefitDealEntity->setOriginUnitGrossPrice($itemTransfer->getOriginUnitGrossPrice());
        $orderSalesItemBenefitDealEntity->setBenefitVoucherAmount($itemTransfer->getTotalUsedBenefitVouchersAmount());
        $orderSalesItemBenefitDealEntity->setType($this->getType());
        $salesOrderItemEntity->addPyzSalesOrderItemBenefitDeals($orderSalesItemBenefitDealEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertBenefitDealsIsApplicable(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getBenefitVoucherDealData()
            && $itemTransfer->getBenefitVoucherDealData()->getIsStore()
            && $itemTransfer->getTotalUsedBenefitVouchersAmount() > 0;
    }

    /**
     * @return string
     */
    private function getType(): string
    {
        return $this->getConfig()->getBenefitVoucherPaymentName();
    }
}
