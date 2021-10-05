<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi;

use Pyz\Client\Money\MoneyClientInterface;
use Pyz\Client\ProductFeed\ProductFeedClientInterface;
use Pyz\Glue\ProductFeedRestApi\Processor\Catalog\CatalogSearchReader;
use Pyz\Glue\ProductFeedRestApi\Processor\Mapper\ProductFeedMapper;
use Pyz\Glue\ProductFeedRestApi\Processor\Reader\ProductReader;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiConfig getConfig()
 */
class ProductFeedRestApiFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Glue\ProductFeedRestApi\Processor\Catalog\CatalogSearchReader
     */
    public function createCatalogSearchReader(): CatalogSearchReader
    {
        return new CatalogSearchReader(
            $this->createProductReader(),
            $this->getResourceBuilder(),
            $this->createProductFeedMapper()
        );
    }

    /**
     * @return \Pyz\Client\ProductFeed\ProductFeedClientInterface
     */
    protected function getProductFeedClient(): ProductFeedClientInterface
    {
        return $this->getProvidedDependency(ProductFeedRestApiDependencyProvider::CLIENT_PRODUCT_FEED);
    }

    /**
     * @return \Pyz\Glue\ProductFeedRestApi\Processor\Mapper\ProductFeedMapper
     */
    public function createProductFeedMapper(): ProductFeedMapper
    {
        return new ProductFeedMapper(
            $this->getConfig(),
            $this->getStore(),
            $this->getMoneyClient()
        );
    }

    /**
     * @return \Pyz\Glue\ProductFeedRestApi\Processor\Reader\ProductReader
     */
    public function createProductReader(): ProductReader
    {
        return new ProductReader(
            $this->getProductFeedClient(),
            $this->getReaderExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(ProductFeedRestApiDependencyProvider::STORE);
    }

    /**
     * @return \Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander\ReaderExpanderInterface[]
     */
    public function getReaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductFeedRestApiDependencyProvider::PLUGINS_READER_EXPANDER);
    }

    /**
     * @return \Pyz\Client\Money\MoneyClientInterface
     */
    public function getMoneyClient(): MoneyClientInterface
    {
        return $this->getProvidedDependency(ProductFeedRestApiDependencyProvider::CLIENT_MONEY);
    }
}
