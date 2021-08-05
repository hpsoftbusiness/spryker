<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business\GroupAction;

use Generated\Shared\Transfer\ProductGroupActionTransfer;

interface GroupActionManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductGroupActionTransfer $groupActionTransfer
     *
     * @return void
     */
    public function groupAction(ProductGroupActionTransfer $groupActionTransfer): void;
}
