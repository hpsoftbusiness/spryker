<?php

namespace Pyz\Zed\CategoryGui\Communication;

use Pyz\Zed\CategoryGui\Communication\Table\CategoryTable;
use Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory as SprykerCategoryGuiCommunicationFactory;
use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable as SprykerCategoryTable;

class CategoryGuiCommunicationFactory extends SprykerCategoryGuiCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Table\CategoryTable
     */
    public function createCategoryTable(): SprykerCategoryTable
    {
        return new CategoryTable($this->getLocaleFacade());
    }
}
