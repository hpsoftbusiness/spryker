<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @param string|array|null $hiddenAttributes
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
