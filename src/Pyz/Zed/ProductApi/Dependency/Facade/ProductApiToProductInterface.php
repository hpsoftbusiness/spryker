<?php

namespace Pyz\Zed\ProductApi\Dependency\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;

interface ProductApiToProductInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return ProductAbstractTransfer
     */
    public function findProductAbstractById(int $idProductAbstract): ProductAbstractTransfer;

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer): ProductUrlTransfer;
}
