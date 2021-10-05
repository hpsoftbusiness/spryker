<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business;

use Generated\Shared\Transfer\ProductGroupActionTransfer;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface as SprykerProductManagementFacadeInterface;

interface ProductManagementFacadeInterface extends SprykerProductManagementFacadeInterface
{
    /**
     * @return array
     */
    public function getDefaultAttributes(): array;

    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    public function groupAction(ProductGroupActionTransfer $groupActionTransfer): void;
}
