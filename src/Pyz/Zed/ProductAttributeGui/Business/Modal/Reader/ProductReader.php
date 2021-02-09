<?php

namespace Pyz\Zed\ProductAttributeGui\Business\Modal\Reader;

use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductBridge;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductInterface;

class ProductReader implements ProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductInterface
     */
    private $attributeGuiToProduct;

    /**
     * ProductReader constructor.
     *
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductInterface $attributeGuiToProduct
     */
    public function __construct(ProductAttributeGuiToProductInterface $attributeGuiToProduct)
    {
        $this->attributeGuiToProduct = $attributeGuiToProduct;
    }

    /**
     * @param string $attributeKey
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return int
     */
    public function getCountAbstractProductUsingAttribute(string $attributeKey): int
    {
        return $this->attributeGuiToProduct
            ->queryProductAbstract()
            ->select('id_product_abstract')
            ->where('attributes like "%\"' . $attributeKey . '\"%" ')
            ->count();
    }

    /**
     * @param string $attributeKey
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return int
     */
    public function getCountProductUsingAttribute(string $attributeKey): int
    {
        return $this->attributeGuiToProduct
            ->queryProduct()
            ->select('id_product')
            ->where('attributes like "%\"' . $attributeKey . '\"%" ')
            ->count();
    }
}
