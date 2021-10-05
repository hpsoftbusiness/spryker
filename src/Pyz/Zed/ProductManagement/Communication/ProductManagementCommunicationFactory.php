<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication;

use Pyz\Zed\ProductManagement\Communication\Form\TableFilter\StatusTableFilterForm;
use Pyz\Zed\ProductManagement\Communication\Form\TableGroupActionForm;
use Pyz\Zed\ProductManagement\Communication\Table\ProductTable;
use Pyz\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper;
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
            $this->getRepository(),
            $this->getProductTableDataExpanderPlugins(),
            $this->createStatusTableFilterForm(),
            $this->createTableGroupActionForm()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTableGroupActionForm()
    {
        return $this->getFormFactory()->create(TableGroupActionForm::class);
    }

    /**
     * @return \Pyz\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper
     */
    public function createProductFormTransferGenerator()
    {
        return new ProductFormTransferMapper(
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createLocaleProvider(),
            $this->getProductFormTransferMapperExpanderPlugins(),
            $this->createProductConcreteSuperAttributeFilterHelper()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createStatusTableFilterForm()
    {
        return $this->getFormFactory()->create(StatusTableFilterForm::class);
    }
}
