<?php

namespace Pyz\Zed\ProductList\Business;

use Pyz\Zed\ProductList\Business\ProductList\ProductListReader;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListBusinessFactory as SprykerProductListBusinessFactory;

class ProductListBusinessFactory extends SprykerProductListBusinessFactory
{
    /**
     * @return ProductListReaderInterface
     */
    public function createProductListReader(): ProductListReaderInterface
    {
        return new ProductListReader(
            $this->getRepository(),
            $this->createProductListCategoryRelationReader(),
            $this->createProductListProductConcreteRelationReader(),
            $this->getProductFacade()
        );
    }
}
