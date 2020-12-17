<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\NavigationStorage;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationStorage\NavigationStorageDependencyProvider as SprykerNavigationStorageDependencyProvider;

class NavigationStorageDependencyProvider extends SprykerNavigationStorageDependencyProvider
{
    public const FACADE_NAVIGATION_FULL = 'FACADE_NAVIGATION_FULL';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addNavigationFullFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNavigationFullFacade(Container $container): Container
    {
        $container->set(static::FACADE_NAVIGATION_FULL, function (Container $container) {
            return $container->getLocator()->navigation()->facade();
        });

        return $container;
    }
}
