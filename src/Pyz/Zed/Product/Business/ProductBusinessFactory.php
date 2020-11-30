<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Pyz\Zed\Product\Business\Transfer\ProductTransferMapper;
use Spryker\Zed\Product\Business\ProductBusinessFactory as SprykerProductBusinessFactory;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 */
class ProductBusinessFactory extends SprykerProductBusinessFactory
{
    /**
     * @return \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    public function createProductTransferMapper()
    {
        return new ProductTransferMapper($this->createAttributeEncoder());
    }
}
