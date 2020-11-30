<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Pyz\Zed\ProductAttribute\Business\Model\Product\ProductAttribute;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory as SprykerProductAttributeBusinessFactory;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeBusinessFactory extends SprykerProductAttributeBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeInterface
     */
    public function createProductAttributeManager()
    {
        return new ProductAttribute(
            $this->createProductAttributeReader(),
            $this->createProductAttributeMapper(),
            $this->createProductReader()
        );
    }
}
