<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Persistence;

use Pyz\Zed\Product\Persistence\Mapper\ProductMapper;
use Spryker\Zed\Product\Persistence\Mapper\ProductMapperInterface;
use Spryker\Zed\Product\Persistence\ProductPersistenceFactory as SprykerProductPersistenceFactory;

class ProductPersistenceFactory extends SprykerProductPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Product\Persistence\Mapper\ProductMapperInterface
     */
    public function createProductMapper(): ProductMapperInterface
    {
        return new ProductMapper($this->getUtilEncodingService());
    }
}
