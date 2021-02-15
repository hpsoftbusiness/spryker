<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Business;

use Pyz\Zed\ProductManagement\Business\Attribute\DefaultAttributeManager;
use Pyz\Zed\ProductManagement\Business\Attribute\DefaultAttributeManagerInterface;
use Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory as SprykerProductManagementBusinessFactory;

class ProductManagementBusinessFactory extends SprykerProductManagementBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductManagement\Business\Attribute\DefaultAttributeManagerInterface
     */
    public function createDefaultAttributeManager(): DefaultAttributeManagerInterface
    {
        return new DefaultAttributeManager();
    }
}
