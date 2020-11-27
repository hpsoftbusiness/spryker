<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup;

use Orm\Zed\CustomerGroupProductList\Persistence\SpyCustomerGroupToProductListQuery;
use Spryker\Zed\CustomerGroup\CustomerGroupDependencyProvider as SprykerCustomerGroupDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerGroupDependencyProvider extends SprykerCustomerGroupDependencyProvider
{
    public const PROPEL_QUERY_CUSTOMER_GROUP_TO_PRODUCT_LIST = 'PROPEL_QUERY_CUSTOMER_GROUP_TO_PRODUCT_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addCustomerGroupToProductListPropelQuery($container);

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
            return SpyCustomerGroupToProductListQuery::create();
        }));

        return $container;
    }
}
