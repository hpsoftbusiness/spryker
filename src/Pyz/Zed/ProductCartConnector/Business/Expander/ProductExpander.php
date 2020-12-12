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

        $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setAbstractSku($productConcreteTransfer->getAbstractSku())
            ->setConcreteAttributes($productConcreteTransfer->getAttributes())
            ->setHiddenConcreteAttributes($productConcreteTransfer->getHiddenAttributes())
            ->setIsAffiliate($productConcreteTransfer->getIsAffiliate())
            ->setName($localizedProductName);
    }
}
