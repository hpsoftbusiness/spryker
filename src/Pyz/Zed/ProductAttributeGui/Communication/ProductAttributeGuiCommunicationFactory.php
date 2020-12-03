<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication;

use Pyz\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory as SprykerProductAttributeGuiCommunicationFactory;

/**
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiCommunicationFactory extends SprykerProductAttributeGuiCommunicationFactory
{
    /**
     * @return \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    public function getPyzProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::FACADE_PYZ_PRODUCT_ATTRIBUTE);
    }
}
