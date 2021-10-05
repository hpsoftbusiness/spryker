<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductAvailabilitiesRestApi;

use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiDependencyProvider as SprykerProductAvailabilitiesRestApiDependencyProvider;

class ProductAvailabilitiesRestApiDependencyProvider extends SprykerProductAvailabilitiesRestApiDependencyProvider
{
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const LOCALE_CLIENT = 'LOCALE_CLIENT';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addClientProductStorage($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addClientProductStorage(Container $container): Container
    {
        $container->set(static::PRODUCT_STORAGE_CLIENT, $container->getLocator()->productStorage()->client());

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::LOCALE_CLIENT, $container->getLocator()->locale()->client());

        return $container;
    }
}
