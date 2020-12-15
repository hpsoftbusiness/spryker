<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategory\Business;

use Pyz\Zed\ProductCategory\Business\Expander\OrderItemExpander;
use Pyz\Zed\ProductCategory\Business\Expander\OrderItemExpanderInterface;
use Spryker\Zed\ProductCategory\Business\ProductCategoryBusinessFactory as SprykerProductCategoryBusinessFactory;

class ProductCategoryBusinessFactory extends SprykerProductCategoryBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductCategory\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander(
            $this->getRepository()
        );
    }
}
