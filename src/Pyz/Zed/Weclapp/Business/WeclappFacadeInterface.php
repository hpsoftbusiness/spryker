<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business;

interface WeclappFacadeInterface
{
    /**
     * @param array $customersIds
     * @param bool $exportNotExisting
     *
     * @return void
     */
    public function exportCustomers(array $customersIds, bool $exportNotExisting): void;

    /**
     * @return void
     */
    public function exportAllCategories(): void;

    /**
     * @param array $categoriesIds
     *
     * @return void
     */
    public function exportCategories(array $categoriesIds): void;

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function changeStocksByWeclapp(array $restWeclappWebhooksAttributesTransfer): void;

    /**
     * @param array $taxRatesIds
     *
     * @return void
     */
    public function exportTaxRates(array $taxRatesIds): void;

    /**
     * @return void
     */
    public function exportAllTaxRates(): void;

    /**
     * @param int $salesOrderId
     *
     * @return void
     */
    public function exportSalesOrder(int $salesOrderId): void;

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function saveDeliveryTrackingCodesByWeclapp(array $restWeclappWebhooksAttributesTransfer): void;

    /**
     * @param array $productsIds
     *
     * @return void
     */
    public function exportProducts(array $productsIds): void;

    /**
     * @return void
     */
    public function exportAllProducts(): void;
}
