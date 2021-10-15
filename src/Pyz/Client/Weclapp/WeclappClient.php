<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;
use Generated\Shared\Transfer\WeclappShipmentTransfer;
use Generated\Shared\Transfer\WeclappTaxTransfer;
use Generated\Shared\Transfer\WeclappWarehouseStockTransfer;
use Generated\Shared\Transfer\WeclappWarehouseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\Weclapp\WeclappFactory getFactory()
 */
class WeclappClient extends AbstractClient implements WeclappClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function postCustomer(CustomerTransfer $customerTransfer): void
    {
        $this->getFactory()->createPostCustomerClient()->postCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer|null
     */
    public function getCustomer(CustomerTransfer $customerTransfer): ?WeclappCustomerTransfer
    {
        return $this->getFactory()->createGetCustomerClient()->getCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer $weclappCustomerTransfer
     *
     * @return void
     */
    public function putCustomer(
        CustomerTransfer $customerTransfer,
        WeclappCustomerTransfer $weclappCustomerTransfer
    ): void {
        $this->getFactory()->createPutCustomerClient()->putCustomer($customerTransfer, $weclappCustomerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function postArticleCategory(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): WeclappArticleCategoryTransfer {
        return $this->getFactory()
            ->createPostArticleCategoryClient()
            ->postArticleCategory($categoryTransfer, $parentWeclappArticleCategoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null
     */
    public function getArticleCategory(CategoryTransfer $categoryTransfer): ?WeclappArticleCategoryTransfer
    {
        return $this->getFactory()->createGetArticleCategoryClient()->getArticleCategory($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function putArticleCategory(
        CategoryTransfer $categoryTransfer,
        WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): WeclappArticleCategoryTransfer {
        return $this->getFactory()->createPutArticleCategoryClient()->putArticleCategory(
            $categoryTransfer,
            $weclappArticleCategoryTransfer,
            $parentWeclappArticleCategoryTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseStockTransfer|null
     */
    public function getWarehouseStock(
        WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
    ): ?WeclappWarehouseStockTransfer {
        return $this->getFactory()
            ->createGetWarehouseStockClient()
            ->getWarehouseStock($weclappWarehouseStockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseTransfer $weclappWarehouseTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseTransfer|null
     */
    public function getWarehouse(
        WeclappWarehouseTransfer $weclappWarehouseTransfer
    ): ?WeclappWarehouseTransfer {
        return $this->getFactory()
            ->createGetWarehouseClient()
            ->getWarehouse($weclappWarehouseTransfer);
    }

    /**
     * Post tax
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer
     */
    public function postTax(TaxRateTransfer $taxRateTransfer): WeclappTaxTransfer
    {
        return $this->getFactory()->createPostTaxClient()->postTax($taxRateTransfer);
    }

    /**
     * Get tax
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappTaxTransfer|null
     */
    public function getTax(TaxRateTransfer $taxRateTransfer): ?WeclappTaxTransfer
    {
        return $this->getFactory()->createGetTaxClient()->getTax($taxRateTransfer);
    }

    /**
     * Post sales order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function postSalesOrder(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createPostSalesOrderClient()
            ->postSalesOrder($orderTransfer);
    }

    /**
     * Get shipment
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WeclappShipmentTransfer $weclappShipmentTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappShipmentTransfer|null
     */
    public function getShipment(
        WeclappShipmentTransfer $weclappShipmentTransfer
    ): ?WeclappShipmentTransfer {
        return $this->getFactory()
            ->createGetShipmentClient()
            ->getShipment($weclappShipmentTransfer);
    }

    /**
     * Post article
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return void
     */
    public function postArticle(ProductConcreteTransfer $productTransfer): void
    {
        $this->getFactory()
            ->createPostArticleClient()
            ->postArticle($productTransfer);
    }

    /**
     * Get article
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer|null
     */
    public function getArticle(ProductConcreteTransfer $productTransfer): ?WeclappArticleTransfer
    {
        return $this->getFactory()
            ->createGetArticleClient()
            ->getArticle($productTransfer);
    }

    /**
     * Put article
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function putArticle(
        ProductConcreteTransfer $productTransfer,
        WeclappArticleTransfer $weclappArticleTransfer
    ): void {
        $this->getFactory()
            ->createPutArticleClient()
            ->putArticle($productTransfer, $weclappArticleTransfer);
    }
}
