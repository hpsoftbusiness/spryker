<?php

namespace Pyz\Zed\ProductAttributeGui\Business\Modal\Reader;

interface ProductReaderInterface
{
    /**
     * @param string $attributeKey
     *
     * @return int
     */
    public function getCountAbstractProductUsingAttribute(string $attributeKey): int;

    /**
     * @param string $attributeKey
     *
     * @return int
     */
    public function getCountProductUsingAttribute(string $attributeKey): int;
}
