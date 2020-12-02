<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade as SprykerProductAttributeFacade;

/**
 * @method \Pyz\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface getRepository()
 */
class ProductAttributeFacade extends SprykerProductAttributeFacade implements ProductAttributeFacadeInterface
{
    /**
     * @param int $idProduct
     * @param string|null $attributeKey
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct, ?string $attributeKey = null)
    {
        return $this->getFactory()
            ->createProductAttributeManager()
            ->getMetaAttributesForProduct($idProduct, $attributeKey);
    }

    /**
     * @param int $idProduct
     * @param array $attributes
     * @param string|null $hiddenAttributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes, ?array $hiddenAttributes = null)
    {
        $this->getFactory()
            ->createProductAttributeWriter()
            ->saveConcreteAttributes($idProduct, $attributes, $hiddenAttributes);
    }
}
