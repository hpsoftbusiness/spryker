<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\ShipmentGui\Communication\Tabs\ShipmentMethodTabs as SprykerShipmentMethodTabs;

class ShipmentMethodTabs extends SprykerShipmentMethodTabs
{
    protected const TAB_DEFAULT_IN_STORES_RELATION_NAME = 'default-in-stores-relation';
    protected const TAB_DEFAULT_IN_STORES_RELATION_TITLE = 'Default in Stores';
    protected const TAB_DEFAULT_IN_STORES_RELATION_TEMPLATE = '@ShipmentGui/_partials/_tabs/tab-default-in-stores-relation.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabsViewTransfer = parent::build($tabsViewTransfer);

        $this->addDefaultInStoresRelationTab($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addDefaultInStoresRelationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::TAB_DEFAULT_IN_STORES_RELATION_NAME);
        $tabItemTransfer->setTemplate(static::TAB_DEFAULT_IN_STORES_RELATION_TEMPLATE);
        $tabItemTransfer->setTitle(static::TAB_DEFAULT_IN_STORES_RELATION_TITLE);
        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
