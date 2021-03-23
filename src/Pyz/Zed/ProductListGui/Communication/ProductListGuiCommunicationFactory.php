<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListGui\Communication;

use Pyz\Zed\ProductListGui\Communication\Table\AssignedProductConcreteTable;
use Pyz\Zed\ProductListGui\Communication\Table\AvailableProductConcreteTable;
use Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory as SprykerProductListGuiCommunicationFactory;

class ProductListGuiCommunicationFactory extends SprykerProductListGuiCommunicationFactory
{
    /**
     * @return \Pyz\Zed\ProductListGui\Communication\Table\AvailableProductConcreteTable
     */
    public function createAvailableProductConcreteTable(): AvailableProductConcreteTable
    {
        return new AvailableProductConcreteTable($this->getLocaleFacade(), $this->getProductPropelQuery());
    }

    /**
     * @return \Pyz\Zed\ProductListGui\Communication\Table\AssignedProductConcreteTable
     */
    public function createAssignedProductConcreteTable(): AssignedProductConcreteTable
    {
        return new AssignedProductConcreteTable($this->getLocaleFacade(), $this->getProductPropelQuery());
    }
}
