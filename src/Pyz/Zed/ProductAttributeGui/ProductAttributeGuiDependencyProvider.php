<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider as SprykerProductAttributeGuiDependencyProvider;

class ProductAttributeGuiDependencyProvider extends SprykerProductAttributeGuiDependencyProvider
{
    public const FACADE_PYZ_PRODUCT_ATTRIBUTE = 'FACADE_PYZ_PRODUCT_ATTRIBUTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addPyzProductAttributeFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPyzProductAttributeFacade(Container $container)
    {
        $container->set(static::FACADE_PYZ_PRODUCT_ATTRIBUTE, function (Container $container) {
            return $container->getLocator()->productAttribute()->facade();
        });

        return $container;
    }
}
