<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\CustomerGroup\Communication\Tabs\CustomerGroupFormTabs as SprykerCustomerGroupFormTabs;

class CustomerGroupFormTabs extends SprykerCustomerGroupFormTabs
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this
            ->addGeneralInformationTab($tabsViewTransfer)
            ->addCustomerAssignmentTab($tabsViewTransfer)
            ->addProductListAssignmentTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(true);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductListAssignmentTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('product-list-assignment')
            ->setTitle('Product Lists')
            ->setTemplate('@CustomerGroup/_partials/product-list-assignment.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
