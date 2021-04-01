<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Communication;

use Pyz\Zed\Product\ProductDependencyProvider;
use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Product\Communication\ProductCommunicationFactory as SprykerProductCommunicationFactory;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface;

class ProductCommunicationFactory extends SprykerProductCommunicationFactory
{
    /**
     * @return \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    public function getUtilEncodingService(): ProductToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface[]
     */
    public function getProductDataHealerPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_DATA_HEALER);
    }
}
