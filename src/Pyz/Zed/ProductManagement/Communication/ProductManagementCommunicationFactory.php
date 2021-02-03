<?php

namespace Pyz\Zed\ProductManagement\Communication;

use Pyz\Zed\ProductManagement\Communication\Table\ProductTable;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory as SprykerProductManagementCommunicationFactory;

class ProductManagementCommunicationFactory extends SprykerProductManagementCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductTable(): AbstractTable
    {
        return new ProductTable(
            $this->getProductQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $this->createProductTypeHelper(),
            $this->getRepository()
        );
    }
}
