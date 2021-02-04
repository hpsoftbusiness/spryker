<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication;

use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeDeleteDataProvider;
use Spryker\Zed\Category\Communication\Form\DeleteType;
use Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory as SprykerProductAttributeGuiCommunicationFactory;
use Pyz\Zed\ProductAttributeGui\Communication\Table\AttributeTable;

class ProductAttributeGuiCommunicationFactory extends SprykerProductAttributeGuiCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createAttributeTable()
    {
        return new AttributeTable(
            $this->getProductAttributeQueryContainer()
        );
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttributeDeleteForm(int $idProductManagementAttribute)
    {
        $attributeDeleteFormDataProvider = $this->createAttributeDeleteFormDataProvider();
        $formFactory = $this->getFormFactory();

        return $formFactory->create(
            DeleteType::class,
            $attributeDeleteFormDataProvider->getData($idProductManagementAttribute),
            $attributeDeleteFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeDeleteDataProvider
     */
    protected function createAttributeDeleteFormDataProvider()
    {
        return new AttributeDeleteDataProvider();
    }
}
