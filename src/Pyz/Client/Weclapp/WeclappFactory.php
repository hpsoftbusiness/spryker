<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp;

use GuzzleHttp\ClientInterface;
use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Weclapp\Client\Article\ArticleHydrator;
use Pyz\Client\Weclapp\Client\Article\ArticleHydratorInterface;
use Pyz\Client\Weclapp\Client\Article\GetArticle\GetArticleClient;
use Pyz\Client\Weclapp\Client\Article\GetArticle\GetArticleClientInterface;
use Pyz\Client\Weclapp\Client\Article\PostArticle\PostArticleClient;
use Pyz\Client\Weclapp\Client\Article\PostArticle\PostArticleClientInterface;
use Pyz\Client\Weclapp\Client\Article\PutArticle\PutArticleClient;
use Pyz\Client\Weclapp\Client\Article\PutArticle\PutArticleClientInterface;
use Pyz\Client\Weclapp\Client\ArticleCategory\ArticleCategoryHydrator;
use Pyz\Client\Weclapp\Client\ArticleCategory\ArticleCategoryHydratorInterface;
use Pyz\Client\Weclapp\Client\ArticleCategory\GetArticleCategory\GetArticleCategoryClient;
use Pyz\Client\Weclapp\Client\ArticleCategory\GetArticleCategory\GetArticleCategoryClientInterface;
use Pyz\Client\Weclapp\Client\ArticleCategory\PostArticleCategory\PostArticleCategoryClient;
use Pyz\Client\Weclapp\Client\ArticleCategory\PostArticleCategory\PostArticleCategoryClientInterface;
use Pyz\Client\Weclapp\Client\ArticleCategory\PutArticleCategory\PutArticleCategoryClient;
use Pyz\Client\Weclapp\Client\ArticleCategory\PutArticleCategory\PutArticleCategoryClientInterface;
use Pyz\Client\Weclapp\Client\Customer\CustomerHydrator;
use Pyz\Client\Weclapp\Client\Customer\CustomerHydratorInterface;
use Pyz\Client\Weclapp\Client\Customer\GetCustomer\GetCustomerClient;
use Pyz\Client\Weclapp\Client\Customer\GetCustomer\GetCustomerClientInterface;
use Pyz\Client\Weclapp\Client\Customer\PostCustomer\PostCustomerClient;
use Pyz\Client\Weclapp\Client\Customer\PostCustomer\PostCustomerClientInterface;
use Pyz\Client\Weclapp\Client\Customer\PutCustomer\PutCustomerClient;
use Pyz\Client\Weclapp\Client\Customer\PutCustomer\PutCustomerClientInterface;
use Pyz\Client\Weclapp\Client\CustomsTariffNumber\CustomsTariffNumberMapper;
use Pyz\Client\Weclapp\Client\CustomsTariffNumber\CustomsTariffNumberMapperInterface;
use Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber\PostCustomsTariffNumberClient;
use Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber\PostCustomsTariffNumberClientInterface;
use Pyz\Client\Weclapp\Client\Manufacturer\ManufacturerMapper;
use Pyz\Client\Weclapp\Client\Manufacturer\ManufacturerMapperInterface;
use Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer\PostManufacturerClient;
use Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer\PostManufacturerClientInterface;
use Pyz\Client\Weclapp\Client\SalesOrder\PostSalesOrder\PostSalesOrderClient;
use Pyz\Client\Weclapp\Client\SalesOrder\PostSalesOrder\PostSalesOrderClientInterface;
use Pyz\Client\Weclapp\Client\SalesOrder\SalesOrderMapper;
use Pyz\Client\Weclapp\Client\SalesOrder\SalesOrderMapperInterface;
use Pyz\Client\Weclapp\Client\Shipment\GetShipment\GetShipmentClient;
use Pyz\Client\Weclapp\Client\Shipment\GetShipment\GetShipmentClientInterface;
use Pyz\Client\Weclapp\Client\Shipment\ShipmentMapper;
use Pyz\Client\Weclapp\Client\Shipment\ShipmentMapperInterface;
use Pyz\Client\Weclapp\Client\Tax\GetTax\GetTaxClient;
use Pyz\Client\Weclapp\Client\Tax\GetTax\GetTaxClientInterface;
use Pyz\Client\Weclapp\Client\Tax\PostTax\PostTaxClient;
use Pyz\Client\Weclapp\Client\Tax\PostTax\PostTaxClientInterface;
use Pyz\Client\Weclapp\Client\Tax\TaxMapper;
use Pyz\Client\Weclapp\Client\Tax\TaxMapperInterface;
use Pyz\Client\Weclapp\Client\Warehouse\GetWarehouse\GetWarehouseClient;
use Pyz\Client\Weclapp\Client\Warehouse\GetWarehouse\GetWarehouseClientInterface;
use Pyz\Client\Weclapp\Client\Warehouse\WarehouseMapper;
use Pyz\Client\Weclapp\Client\Warehouse\WarehouseMapperInterface;
use Pyz\Client\Weclapp\Client\WarehouseStock\GetWarehouseStock\GetWarehouseStockClient;
use Pyz\Client\Weclapp\Client\WarehouseStock\GetWarehouseStock\GetWarehouseStockClientInterface;
use Pyz\Client\Weclapp\Client\WarehouseStock\WarehouseStockMapper;
use Pyz\Client\Weclapp\Client\WarehouseStock\WarehouseStockMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

/**
 * @method \Pyz\Client\Weclapp\WeclappConfig getConfig()
 */
class WeclappFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\Weclapp\Client\Customer\PostCustomer\PostCustomerClientInterface
     */
    public function createPostCustomerClient(): PostCustomerClientInterface
    {
        return new PostCustomerClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createCustomerHydrator()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Customer\GetCustomer\GetCustomerClientInterface
     */
    public function createGetCustomerClient(): GetCustomerClientInterface
    {
        return new GetCustomerClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createCustomerHydrator()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Customer\PutCustomer\PutCustomerClientInterface
     */
    public function createPutCustomerClient(): PutCustomerClientInterface
    {
        return new PutCustomerClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createCustomerHydrator()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\ArticleCategory\PostArticleCategory\PostArticleCategoryClientInterface
     */
    public function createPostArticleCategoryClient(): PostArticleCategoryClientInterface
    {
        return new PostArticleCategoryClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createArticleCategoryHydrator()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\ArticleCategory\GetArticleCategory\GetArticleCategoryClientInterface
     */
    public function createGetArticleCategoryClient(): GetArticleCategoryClientInterface
    {
        return new GetArticleCategoryClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createArticleCategoryHydrator()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\ArticleCategory\PutArticleCategory\PutArticleCategoryClientInterface
     */
    public function createPutArticleCategoryClient(): PutArticleCategoryClientInterface
    {
        return new PutArticleCategoryClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createArticleCategoryHydrator()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\WarehouseStock\GetWarehouseStock\GetWarehouseStockClientInterface
     */
    public function createGetWarehouseStockClient(): GetWarehouseStockClientInterface
    {
        return new GetWarehouseStockClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createWarehouseStockMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Warehouse\GetWarehouse\GetWarehouseClientInterface
     */
    public function createGetWarehouseClient(): GetWarehouseClientInterface
    {
        return new GetWarehouseClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createWarehouseMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Tax\PostTax\PostTaxClientInterface
     */
    public function createPostTaxClient(): PostTaxClientInterface
    {
        return new PostTaxClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createTaxMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Tax\GetTax\GetTaxClientInterface
     */
    public function createGetTaxClient(): GetTaxClientInterface
    {
        return new GetTaxClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createTaxMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\SalesOrder\PostSalesOrder\PostSalesOrderClientInterface
     */
    public function createPostSalesOrderClient(): PostSalesOrderClientInterface
    {
        return new PostSalesOrderClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createSalesOrderMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Shipment\GetShipment\GetShipmentClientInterface
     */
    public function createGetShipmentClient(): GetShipmentClientInterface
    {
        return new GetShipmentClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createShipmentMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Article\PostArticle\PostArticleClientInterface
     */
    public function createPostArticleClient(): PostArticleClientInterface
    {
        return new PostArticleClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createArticleHydrator(),
            $this->createPostCustomsTariffNumberClient(),
            $this->createPostManufacturerClient()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Article\GetArticle\GetArticleClientInterface
     */
    public function createGetArticleClient(): GetArticleClientInterface
    {
        return new GetArticleClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createArticleHydrator(),
            $this->createPostCustomsTariffNumberClient(),
            $this->createPostManufacturerClient()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Article\PutArticle\PutArticleClientInterface
     */
    public function createPutArticleClient(): PutArticleClientInterface
    {
        return new PutArticleClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createArticleHydrator(),
            $this->createPostCustomsTariffNumberClient(),
            $this->createPostManufacturerClient()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\CustomsTariffNumber\PostCustomsTariffNumber\PostCustomsTariffNumberClientInterface
     */
    public function createPostCustomsTariffNumberClient(): PostCustomsTariffNumberClientInterface
    {
        return new PostCustomsTariffNumberClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createCustomsTariffNumberMapper()
        );
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Manufacturer\PostManufacturer\PostManufacturerClientInterface
     */
    public function createPostManufacturerClient(): PostManufacturerClientInterface
    {
        return new PostManufacturerClient(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getApiLogClient(),
            $this->getErrorLogger(),
            $this->createManufacturerMapper()
        );
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getHttpClient(): ClientInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected function getErrorLogger(): ErrorLoggerInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::ERROR_LOGGER);
    }

    /**
     * @return \Pyz\Client\ApiLog\ApiLogClientInterface
     */
    protected function getApiLogClient(): ApiLogClientInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::CLIENT_API_LOG);
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Customer\CustomerHydratorInterface
     */
    protected function createCustomerHydrator(): CustomerHydratorInterface
    {
        return new CustomerHydrator($this->getConfig());
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\ArticleCategory\ArticleCategoryHydratorInterface
     */
    protected function createArticleCategoryHydrator(): ArticleCategoryHydratorInterface
    {
        return new ArticleCategoryHydrator();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\WarehouseStock\WarehouseStockMapperInterface
     */
    protected function createWarehouseStockMapper(): WarehouseStockMapperInterface
    {
        return new WarehouseStockMapper();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Warehouse\WarehouseMapperInterface
     */
    protected function createWarehouseMapper(): WarehouseMapperInterface
    {
        return new WarehouseMapper();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Tax\TaxMapperInterface
     */
    protected function createTaxMapper(): TaxMapperInterface
    {
        return new TaxMapper();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\SalesOrder\SalesOrderMapperInterface
     */
    protected function createSalesOrderMapper(): SalesOrderMapperInterface
    {
        return new SalesOrderMapper();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Shipment\ShipmentMapperInterface
     */
    protected function createShipmentMapper(): ShipmentMapperInterface
    {
        return new ShipmentMapper();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Article\ArticleHydratorInterface
     */
    protected function createArticleHydrator(): ArticleHydratorInterface
    {
        return new ArticleHydrator();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\CustomsTariffNumber\CustomsTariffNumberMapperInterface
     */
    protected function createCustomsTariffNumberMapper(): CustomsTariffNumberMapperInterface
    {
        return new CustomsTariffNumberMapper();
    }

    /**
     * @return \Pyz\Client\Weclapp\Client\Manufacturer\ManufacturerMapperInterface
     */
    protected function createManufacturerMapper(): ManufacturerMapperInterface
    {
        return new ManufacturerMapper();
    }
}
