<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup;

use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery;
use Spryker\Zed\CustomerGroup\CustomerGroupDependencyProvider as SprykerCustomerGroupDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerGroupDependencyProvider extends SprykerCustomerGroupDependencyProvider
{
    public const FACADE_CUSTOMER_GROUP_PRODUCT_LIST = 'FACADE_CUSTOMER_GROUP_PRODUCT_LIST';

    public const PROPEL_QUERY_CUSTOMER_GROUP_TO_PRODUCT_LIST = 'PROPEL_QUERY_CUSTOMER_GROUP_TO_PRODUCT_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCustomerGroupProductListFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addCustomerGroupToProductListPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerGroupProductListFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER_GROUP_PRODUCT_LIST, function (Container $container) {
            return $container->getLocator()->customerGroupProductList()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerGroupToProductListPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CUSTOMER_GROUP_TO_PRODUCT_LIST, $container->factory(function () {
            return PyzCustomerGroupToProductListQuery::create();
        }));

        return $container;
    }
}
