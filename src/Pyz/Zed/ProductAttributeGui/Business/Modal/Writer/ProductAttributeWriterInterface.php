<?php

namespace Pyz\Zed\ProductAttributeGui\Business\Modal\Writer;

/**
 * Interface ProductAttributeWriterInterface
 *
 * @package Pyz\Zed\ProductAttributeGui\Business\Modal\Writer
 */
interface ProductAttributeWriterInterface
{
    /**
     * @param int $idProductManagementAttribute
     */
    public function delete(int $idProductManagementAttribute): void;
}
