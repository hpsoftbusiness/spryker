<?php

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
     */
    public function delete(int $idProductManagementAttribute): void;
}
