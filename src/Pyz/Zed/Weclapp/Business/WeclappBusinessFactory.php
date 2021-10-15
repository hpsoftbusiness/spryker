<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business;

use Pyz\Client\Weclapp\WeclappClientInterface;
use Pyz\Zed\Category\Business\CategoryFacadeInterface;
use Pyz\Zed\Country\Business\CountryFacadeInterface;
use Pyz\Zed\Oms\Business\OmsFacadeInterface;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Pyz\Zed\Stock\Business\StockFacadeInterface;
use Pyz\Zed\Weclapp\Business\Exporter\CategoryExporter;
use Pyz\Zed\Weclapp\Business\Exporter\CategoryExporterInterface;
use Pyz\Zed\Weclapp\Business\Exporter\CustomerExporter;
use Pyz\Zed\Weclapp\Business\Exporter\CustomerExporterInterface;
use Pyz\Zed\Weclapp\Business\Exporter\ProductExporter;
use Pyz\Zed\Weclapp\Business\Exporter\ProductExporterInterface;
use Pyz\Zed\Weclapp\Business\Exporter\SalesOrderExporter;
use Pyz\Zed\Weclapp\Business\Exporter\SalesOrderExporterInterface;
use Pyz\Zed\Weclapp\Business\Exporter\TaxRateExporter;
use Pyz\Zed\Weclapp\Business\Exporter\TaxRateExporterInterface;
use Pyz\Zed\Weclapp\Business\Importer\DeliveryTrackingCodeImporter;
use Pyz\Zed\Weclapp\Business\Importer\DeliveryTrackingCodeImporterInterface;
use Pyz\Zed\Weclapp\Business\Importer\StockImporter;
use Pyz\Zed\Weclapp\Business\Importer\StockImporterInterface;
use Pyz\Zed\Weclapp\WeclappDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Tax\Business\TaxFacadeInterface;

/**
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface getRepository()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 */
class WeclappBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\Weclapp\Business\Exporter\CustomerExporterInterface
     */
    public function createCustomerExporter(): CustomerExporterInterface
    {
        return new CustomerExporter(
            $this->getWeclappClient(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Weclapp\Business\Exporter\CategoryExporterInterface
     */
    public function createCategoryExporter(): CategoryExporterInterface
    {
        return new CategoryExporter(
            $this->getWeclappClient(),
            $this->getCategoryFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Weclapp\Business\Importer\StockImporterInterface
     */
    public function createStockImporter(): StockImporterInterface
    {
        return new StockImporter(
            $this->getWeclappClient(),
            $this->getStockFacade(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Weclapp\Business\Exporter\TaxRateExporterInterface
     */
    public function createTaxRateExporter(): TaxRateExporterInterface
    {
        return new TaxRateExporter(
            $this->getWeclappClient(),
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Weclapp\Business\Exporter\SalesOrderExporterInterface
     */
    public function createSalesOrderExporter(): SalesOrderExporterInterface
    {
        return new SalesOrderExporter(
            $this->getWeclappClient(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Weclapp\Business\Importer\DeliveryTrackingCodeImporterInterface
     */
    public function createDelivereryTrackingCodeImporter(): DeliveryTrackingCodeImporterInterface
    {
        return new DeliveryTrackingCodeImporter(
            $this->getWeclappClient(),
            $this->getConfig(),
            $this->getSalesFacade(),
            $this->getOmsFacade()
        );
    }

    /**
     * @return \Pyz\Zed\Weclapp\Business\Exporter\ProductExporterInterface
     */
    public function createProductExporter(): ProductExporterInterface
    {
        return new ProductExporter(
            $this->getWeclappClient(),
            $this->getProductFacade(),
            $this->getProductCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getCountryFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected function getWeclappClient(): WeclappClientInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::CLIENT_WECLAPP);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    protected function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Pyz\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function getCategoryFacade(): CategoryFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Pyz\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected function getTaxFacade(): TaxFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Pyz\Zed\Oms\Business\OmsFacadeInterface
     */
    protected function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    protected function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected function getProductCategoryFacade(): ProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Pyz\Zed\Country\Business\CountryFacadeInterface
     */
    protected function getCountryFacade(): CountryFacadeInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::FACADE_COUNTRY);
    }
}
