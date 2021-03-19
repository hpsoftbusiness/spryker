<?php

namespace Pyz\Zed\ProductApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Pyz\Zed\ProductApi\Dependency\Facade\ProductApiToProductBridge;
use Pyz\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiBridge;

class ProductApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_API = 'QUERY_CONTAINER_API';

    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_API = 'FACADE_API';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';

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
        $container = $this->provideApiQueryBuilderQueryContainer($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function provideApiQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_API, function (Container $container) {
            return new ProductApiToApiBridge($container->getLocator()->api()->queryContainer());
        });

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function provideProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductApiToProductBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function provideApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_API, function (Container $container) {
            return $container->getLocator()->api()->facade();
        });

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function provideLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function provideProductCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_CATEGORY, function (Container $container) {
            return $container->getLocator()->productCategory()->facade();
        });

        return $container;
    }
}
