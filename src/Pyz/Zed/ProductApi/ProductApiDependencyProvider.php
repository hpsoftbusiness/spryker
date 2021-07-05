<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi;

use Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductMime;
use Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiBridgeMime;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ProductApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_API = 'QUERY_CONTAINER_API';

    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_API = 'FACADE_API';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->provideApiFacade($container);
        $container = $this->provideApiQueryContainer($container);
        $container = $this->provideProductFacade($container);
        $container = $this->provideLocaleFacade($container);
        $container = $this->provideProductCategoryFacade($container);
        $container = $this->providePriceProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->provideApiQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_API, function (Container $container) {
            return new ProductApiToApiBridgeMime($container->getLocator()->api()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductApiToProductMime($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_API, function (Container $container) {
            return $container->getLocator()->api()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideProductCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_CATEGORY, function (Container $container) {
            return $container->getLocator()->productCategory()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function providePriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return $container->getLocator()->priceProduct()->facade();
        });

        return $container;
    }
}
