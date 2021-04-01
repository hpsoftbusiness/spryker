<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Pyz\Zed\ProductAttribute\Business\Model\Attribute\AttributeReader;
use Pyz\Zed\ProductAttribute\Business\Model\Attribute\AttributeReaderInterface;
use Pyz\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter;
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
            $this->getUtilSanitizeXssService()
        );
    }

    /**
     * @return \Pyz\Zed\ProductAttribute\Business\Model\Attribute\AttributeReaderInterface
     */
    public function createAttributeReader(): AttributeReaderInterface
    {
        return new AttributeReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createProductAttributeTransferGenerator()
        );
    }
}
