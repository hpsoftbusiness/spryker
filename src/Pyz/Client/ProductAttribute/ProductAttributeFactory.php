<?php

namespace Pyz\Client\ProductAttribute;

use Pyz\Client\ProductAttribute\Zed\ProductAttributeZedStub;
use Pyz\Client\ProductAttribute\Zed\ProductAttributeZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ProductAttributeFactory extends AbstractFactory
{
    /**
     * @return ProductAttributeZedStubInterface
     */
    public function createProductAttributeZedStub(): ProductAttributeZedStubInterface
    {
        return new ProductAttributeZedStub($this->getZedRequestClient());
    }

    /**
     * @return ZedRequestClientInterface
     */
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
