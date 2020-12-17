<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\NavigationStorage\Communication\Plugin\ProductListStorage;

use Pyz\Zed\ProductListStorage\Dependency\Plugin\ProductListProductAbstractAfterPublishPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\NavigationStorage\Communication\NavigationStorageCommunicationFactory getFactory()
 * @method \Pyz\Zed\NavigationStorage\NavigationStorageConfig getConfig()
 * @method \Spryker\Zed\NavigationStorage\Business\NavigationStorageFacadeInterface getFacade()
 */
class CategoryNavigationProductListStoragePublishTriggerPlugin extends AbstractPlugin implements ProductListProductAbstractAfterPublishPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function execute(): void
    {
        $this->getFacade()->publish(
            $this->getCategoryNavigationIds()
        );
    }

    /**
     * @return int[]
     */
    protected function getCategoryNavigationIds(): array
    {
        $categoryNavigationKeys = $this->getConfig()->getCategoryNavigationKeys();

        return $this->getFactory()->getNavigationFullFacade()->getNavigationIdsByKeys($categoryNavigationKeys);
    }
}
