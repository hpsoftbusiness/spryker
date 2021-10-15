<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\Weclapp\Business\WeclappBusinessFactory getFactory()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface getRepository()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface getEntityManager()
 */
class WeclappFacade extends AbstractFacade implements WeclappFacadeInterface
{
    /**
     * @param array $customersIds
     * @param bool $exportNotExisting
     *
     * @return void
     */
    public function exportCustomers(array $customersIds, bool $exportNotExisting): void
    {
        $this->getFactory()
            ->createCustomerExporter()
            ->exportCustomers($customersIds, $exportNotExisting);
    }

    /**
     * @return void
     */
    public function exportAllCategories(): void
    {
        $this->getFactory()
            ->createCategoryExporter()
            ->exportAllCategories();
    }

    /**
     * @param array $categoriesIds
     *
     * @return void
     */
    public function exportCategories(array $categoriesIds): void
    {
        $this->getFactory()
            ->createCategoryExporter()
            ->exportCategories($categoriesIds);
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function changeStocksByWeclapp(array $restWeclappWebhooksAttributesTransfer): void
    {
        $this->getFactory()
            ->createStockImporter()
            ->changeStocksByWeclapp($restWeclappWebhooksAttributesTransfer);
    }

    /**
     * @param array $taxRatesIds
     *
     * @return void
     */
    public function exportTaxRates(array $taxRatesIds): void
    {
        $this->getFactory()
            ->createTaxRateExporter()
            ->exportTaxRates($taxRatesIds);
    }

    /**
     * @return void
     */
    public function exportAllTaxRates(): void
    {
        $this->getFactory()
            ->createTaxRateExporter()
            ->exportAllTaxRates();
    }

    /**
     * @param int $salesOrderId
     *
     * @return void
     */
    public function exportSalesOrder(int $salesOrderId): void
    {
        $this->getFactory()
            ->createSalesOrderExporter()
            ->exportSalesOrder($salesOrderId);
    }

    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[] $restWeclappWebhooksAttributesTransfer
     *
     * @return void
     */
    public function saveDeliveryTrackingCodesByWeclapp(array $restWeclappWebhooksAttributesTransfer): void
    {
        $this->getFactory()
            ->createDelivereryTrackingCodeImporter()
            ->saveDeliveryTrackingCodesByWeclapp($restWeclappWebhooksAttributesTransfer);
    }

    /**
     * @param array $productsIds
     *
     * @return void
     */
    public function exportProducts(array $productsIds): void
    {
        $this->getFactory()
            ->createProductExporter()
            ->exportProducts($productsIds);
    }

    /**
     * @return void
     */
    public function exportAllProducts(): void
    {
        $this->getFactory()
            ->createProductExporter()
            ->exportAllProducts();
    }
}
