<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication;

use Pyz\Zed\Product\Persistence\ProductQueryContainerInterface;
use Pyz\Zed\ProductAttributeGui\Communication\Form\AttributeForm;
use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeDeleteDataProvider;
use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeFormDataProvider;
use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider;
use Pyz\Zed\ProductAttributeGui\Communication\Table\AttributeTable;
use Spryker\Zed\Category\Communication\Form\DeleteType;
use Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory as SprykerProductAttributeGuiCommunicationFactory;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;

class ProductAttributeGuiCommunicationFactory extends SprykerProductAttributeGuiCommunicationFactory
{
    /**
     * @return \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Pyz\Zed\ProductAttributeGui\Communication\Table\AttributeTable
     */
    public function createAttributeTable(): AttributeTable
    {
        return new AttributeTable($this->getProductAttributeQueryContainer());
    }

    /**
     * @return mixed|\Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    public function getProductAttributeQueryContainer(): ProductAttributeGuiToProductAttributeQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_ATTRIBUTE);
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

    /**
     * @return \Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider
     */
    public function createAttributeTranslationFormCollectionDataProvider(): AttributeTranslationFormCollectionDataProvider
    {
        return new AttributeTranslationFormCollectionDataProvider(
            $this->getProductAttributeFacade(),
            $this->getProductAttributeQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAttributeForm(array $data = [], array $options = [])
    {
        return $this->getFormFactory()->create(AttributeForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeFormDataProvider
     */
    public function createAttributeFormDataProvider()
    {
        return new AttributeFormDataProvider(
            $this->getProductAttributeQueryContainer(),
            $this->getProductAttributeFacade()
        );
    }
}
