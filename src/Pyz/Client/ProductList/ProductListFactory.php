<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductList;

use Pyz\Client\ProductList\Zed\ProductListZedStub;
use Pyz\Client\ProductList\Zed\ProductListZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ProductListFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\ProductList\Zed\ProductListZedStubInterface
     */
    public function createProductListZedStub(): ProductListZedStubInterface
    {
        return new ProductListZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ProductListDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
