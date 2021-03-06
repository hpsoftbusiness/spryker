<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
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
     * Specification:
     * - If benefit deals were applied for the order, deals total amounts are persisted to pyz_sales_order_benefit_deal table.
     *
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
     * Specification:
     * - Expands OrderTransfer with order benefit deal data.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithBenefitDeal(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createBenefitDealReader()->hydrateOrderWithBenefitDeal($orderTransfer);
    }

    /**
     * Specification:
     * - Expands SpySalesOrderItemEntityTransfer with item benefit deal data if benefit deals were applied fot the item.
     *
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
     * Specification:
     * - Expands ItemTransfers with benefit deal data from the database.
     *
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

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        return $this->getFactory()
            ->createProductAbstractRelationReader()
            ->findProductLabelProductAbstractRelationChanges();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findInsteadOfProductLabelProductAbstractRelationChanges(): array
    {
        return $this->getFactory()
            ->createProductAbstractRelationReader()
            ->findInsteadOfProductLabelProductAbstractRelationChanges();
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string|null $currencyIsoCode
     *
     * @return void
     */
    public function expandItemsWithBenefitDealsData(iterable $itemTransfers, ?string $currencyIsoCode = null): void
    {
        $this->getFactory()->createItemBenefitExpander()->expandItems($itemTransfers, $currencyIsoCode);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this
            ->getFactory()
            ->createItemBenefitPriceExpander()
            ->expandItems($cartChangeTransfer);
    }
}
