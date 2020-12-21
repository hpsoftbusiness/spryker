<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector\Business\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCartConnector\Business\Expander\ProductExpander as SprykerProductExpander;

class ProductExpander extends SprykerProductExpander
{
    protected const KEY_SELLABLE_ATTRIBUTE = 'sellable_';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandItemWithProductConcrete(ProductConcreteTransfer $productConcreteTransfer, ItemTransfer $itemTransfer)
    {
        $localizedProductName = $this->productFacade->getLocalizedProductConcreteName(
            $productConcreteTransfer,
            $this->localeFacade->getCurrentLocale()
        );

        $itemTransfer
            ->setId($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setAbstractSku($productConcreteTransfer->getAbstractSku())
            ->setConcreteAttributes($productConcreteTransfer->getAttributes())
            ->setConcreteLocalizedAttributes($this->getLocalizedAttributes($productConcreteTransfer))
            ->setIsAffiliate($productConcreteTransfer->getIsAffiliate())
            ->setName($localizedProductName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array
     */
    protected function getLocalizedAttributes(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $localizedAttributes = [];
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $productConcretelocalizedAttributes) {
            $localizedAttributesData = json_decode($productConcretelocalizedAttributes->getAttributes(), true);
            $filteredLocalizedAttributesData = $this->filterSellableAttributes($localizedAttributesData);
            $localizedAttributes[$productConcretelocalizedAttributes->getLocale()->getLocaleName()] = $filteredLocalizedAttributesData;
        }

        return $localizedAttributes;
    }

    /**
     * @param array $localizedAttributes
     *
     * @return array
     */
    protected function filterSellableAttributes(array $localizedAttributes): array
    {
        foreach ($localizedAttributes as $localizedAttributeKey => $localizedAttributeValue) {
            if (strpos($localizedAttributeKey, static::KEY_SELLABLE_ATTRIBUTE) !== false && !boolval($localizedAttributeValue)) {
                unset($localizedAttributes[$localizedAttributeKey]);
            }
        }

        return $localizedAttributes;
    }
}
