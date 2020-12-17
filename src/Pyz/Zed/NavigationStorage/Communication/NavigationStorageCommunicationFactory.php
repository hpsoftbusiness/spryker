<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\NavigationStorage\Communication;

use Pyz\Zed\Navigation\Business\NavigationFacadeInterface;
use Pyz\Zed\NavigationStorage\NavigationStorageDependencyProvider;
use Spryker\Zed\NavigationStorage\Communication\NavigationStorageCommunicationFactory as SprykerNavigationStorageCommunicationFactory;

class NavigationStorageCommunicationFactory extends SprykerNavigationStorageCommunicationFactory
{
    /**
     * @return \Pyz\Zed\Navigation\Business\NavigationFacadeInterface
     */
    public function getNavigationFullFacade(): NavigationFacadeInterface
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::FACADE_NAVIGATION_FULL);
    }
}
