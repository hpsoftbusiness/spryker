<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Pyz\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter;
use Pyz\Zed\ProductAttribute\Business\Model\Attribute\AttributeReader;
use Pyz\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapper;
use Pyz\Zed\ProductAttribute\ProductAttributeDependencyProvider;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory as SprykerProductAttributeBusinessFactory;

/**
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeBusinessFactory extends SprykerProductAttributeBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriterInterface
     */
    public function createProductAttributeWriter()
    {
        return new ProductAttributeWriter(
            $this->createProductAttributeReader(),
            $this->getLocaleFacade(),
            $this->getProductFacade(),
            $this->createProductReader(),
            $this->getUtilSanitizeXssService(),
            $this->getProductAttributePreSaveCheckPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\ProductAttribute\Dependency\Plugin\ProductAttributePreSaveCheckPluginInterface[]
     */
    public function getProductAttributePreSaveCheckPlugins(): array
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::PLUGINS_PRODUCT_ATTRIBUTE_PRE_SAVE_CHECK);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeReaderInterface
     */
    public function createAttributeReader()
    {
        return new AttributeReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createProductAttributeTransferGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface
     */
    protected function createProductAttributeTransferGenerator()
    {
        return new ProductAttributeTransferMapper(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createAttributeGlossaryKeyBuilder()
        );
    }
}
