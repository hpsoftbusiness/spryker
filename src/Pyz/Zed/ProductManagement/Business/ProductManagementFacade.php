<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business;

use Generated\Shared\Transfer\ProductGroupActionTransfer;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacade as SprykerProductManagementFacade;

/**
 * @method \Pyz\Zed\ProductManagement\Business\ProductManagementBusinessFactory getFactory()
 */
class ProductManagementFacade extends SprykerProductManagementFacade implements ProductManagementFacadeInterface
{
    /**
     * @return array
     */
    public function getDefaultAttributes(): array
    {
        return $this->getFactory()
            ->createDefaultAttributeManager()
            ->getDefaultAttributes();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    public function groupAction(ProductGroupActionTransfer $groupActionTransfer): void
    {
        $this->getFactory()
            ->createGroupActionManager()
            ->groupAction($groupActionTransfer);
    }
}
