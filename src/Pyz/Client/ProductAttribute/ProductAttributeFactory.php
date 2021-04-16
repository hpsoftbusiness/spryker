<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttribute;

use Pyz\Client\ProductAttribute\Zed\ProductAttributeZedStub;
use Pyz\Client\ProductAttribute\Zed\ProductAttributeZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ProductAttributeFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\ProductAttribute\Zed\ProductAttributeZedStubInterface
     */
    public function createProductAttributeZedStub(): ProductAttributeZedStubInterface
    {
        return new ProductAttributeZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
