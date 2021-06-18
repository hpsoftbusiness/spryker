<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage;

use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductListStorage\ProductListStorageDependencyProvider as SprykerProductListStorageDependencyProvider;

class ProductListStorageDependencyProvider extends SprykerProductListStorageDependencyProvider
{
    public const CLIENT_CUSTOMER_GROUP_STORAGE = 'CLIENT_CUSTOMER_GROUP_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addCustomerGroupStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCustomerGroupStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER_GROUP_STORAGE, function (Container $container) {
            return $container->getLocator()->customerGroupStorage()->client();
        });

        return $container;
    }
}
