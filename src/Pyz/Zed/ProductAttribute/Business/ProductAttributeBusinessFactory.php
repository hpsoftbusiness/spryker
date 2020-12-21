<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductAttribute\Business;

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
}
