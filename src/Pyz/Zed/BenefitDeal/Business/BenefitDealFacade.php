<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\BenefitDeal\Business\BenefitDealBusinessFactory getFactory()
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface getRepository()
 * @method \Pyz\Zed\BenefitDeal\Persistence\BenefitDealEntityManagerInterface getEntityManager()
 */
class BenefitDealFacade extends AbstractFacade implements BenefitDealFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderBenefitDealFromQuote(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $this->getFactory()->createBenefitDealWriter()->saveSalesOrderBenefitDealFromQuote(
            $saveOrderTransfer,
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithBenefitDeal(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createBenefitDealReader()->hydrateOrderWithBenefitDeal($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItemPreSave(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer {
        return $this->getFactory()->createItemPreSaveExpander()->expandOrderItem(
            $quoteTransfer,
            $itemTransfer,
            $salesOrderItemEntity
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItems(array $itemTransfers): array
    {
        return $this->getFactory()->createItemBenefitDealReader()->hydrateOrderItemsWithBenefitDeals($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function equalizeQuoteItemsBenefitDealUsageFlags(
        QuoteTransfer $resultQuoteTransfer,
        QuoteTransfer $sourceQuoteTransfer
    ): void {
        $this->getFactory()->createQuoteEqualizer()->equalize($resultQuoteTransfer, $sourceQuoteTransfer);
    }
}
