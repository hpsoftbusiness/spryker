<?php

namespace Pyz\Zed\ProductApi\Dependency\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Zed\Product\Business\ProductFacadeInterface;

class ProductApiToProductBridge implements ProductApiToProductInterface
{
    /**
     * @var ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param ProductFacadeInterface $productFacade
     */
    public function __construct(ProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return ProductAbstractTransfer
     */
    public function findProductAbstractById(int $idProductAbstract): ProductAbstractTransfer
    {
        return $this->productFacade->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer): ProductUrlTransfer
    {
        return $this->productFacade->getProductUrl($productAbstractTransfer);
    }
}
