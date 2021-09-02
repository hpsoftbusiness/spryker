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

interface BenefitDealFacadeInterface
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
    ): void;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderWithBenefitDeal(OrderTransfer $orderTransfer): OrderTransfer;

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
    ): SpySalesOrderItemEntityTransfer;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItems(array $itemTransfers): array;

    /**
     * Specification:
     * - Hydrate $resultQuoteTransfer items with benefit deals usage flags from source quote items.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function equalizeQuoteItemsBenefitDealUsageFlags(
        QuoteTransfer $resultQuoteTransfer,
        QuoteTransfer $sourceQuoteTransfer
    ): void;

    /**
     * Specification:
     * - Returns a list of Product Label - Product Abstract relation to assign and deassign.
     * - The results are used to persist product label relation changes into database.
     * - The plugin is called when the ProductLabelRelationUpdaterConsole command is executed.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array;

    /**
     * Specification:
     * - Returns a list of Product Label - Product Abstract relation to assign and deassign.
     * - The results are used to persist product label relation changes into database.
     * - The plugin is called when the ProductLabelRelationUpdaterConsole command is executed.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findInsteadOfProductLabelProductAbstractRelationChanges(): array;

    /**
     * Specification:
     * - Expands ItemTransfers with BenefitVoucherDealDataTransfer or ShoppingPointsDealTransfer.
     * - Hydrates price property of either of these transfers with BENEFIT deal price.
     *
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string|null $currencyIsoCode
     *
     * @return void
     */
    public function expandItemsWithBenefitDealsData(iterable $itemTransfers, ?string $currencyIsoCode = null): void;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitPrice(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
