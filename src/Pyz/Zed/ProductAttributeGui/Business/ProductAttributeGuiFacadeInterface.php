<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Business;

/**
 * Interface AttributeFacadeInterface
 *
 * @package Pyz\Zed\ProductAttributeGui\Business
 */
interface ProductAttributeGuiFacadeInterface
{
    /**
     * @param int $idProductManagementAttribute
     *
     * @return void
     */
    public function delete(int $idProductManagementAttribute): void;
}
