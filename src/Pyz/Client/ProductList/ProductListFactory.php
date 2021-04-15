<?php

namespace Pyz\Client\ProductList;

use Pyz\Client\ProductList\Zed\ProductListZedStub;
use Pyz\Client\ProductList\Zed\ProductListZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ProductListFactory extends AbstractFactory
{
    /**
     * @return ProductListZedStubInterface
     */
    public function createProductListZedStub(): ProductListZedStubInterface
    {
        return new ProductListZedStub($this->getZedRequestClient());
    }

    /**
     * @return ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
