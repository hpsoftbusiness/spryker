<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication;

use Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReader;
use Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReaderInterface;
use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeDeleteDataProvider;
use Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerBridge;
use Spryker\Zed\Category\Communication\Form\DeleteType;
use Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory as SprykerProductAttributeGuiCommunicationFactory;
use Pyz\Zed\ProductAttributeGui\Communication\Table\AttributeTable;
use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductInterface;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;

class ProductAttributeGuiCommunicationFactory extends SprykerProductAttributeGuiCommunicationFactory
{
    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @return \Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReaderInterface
     */
    public function createProductReader()
    {
        return new ProductReader($this->getProductQueryContainer());
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     */
    public function getProductQueryContainer(): ProductAttributeGuiToProductInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @return \Pyz\Zed\ProductAttributeGui\Communication\Table\AttributeTable
     */
    public function createAttributeTable(): AttributeTable
    {
        return new AttributeTable(
            $this->getProductAttributeQueryContainer(),
            $this->createProductReader()
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
}
