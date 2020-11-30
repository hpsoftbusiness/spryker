<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Transfer;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapper as SprykerProductTransferMapper;

class ProductTransferMapper extends SprykerProductTransferMapper
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer $productEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapSpyProductEntityTransferToProductConcreteTransfer(SpyProductEntityTransfer $productEntityTransfer): ProductConcreteTransfer
    {
        $productTransfer = parent::mapSpyProductEntityTransferToProductConcreteTransfer($productEntityTransfer);

        $hiddenAttributes = $this->attributeEncoder->decodeAttributes($productEntityTransfer->getHiddenAttributes());

        $productTransfer
            ->setHiddenAttributes($hiddenAttributes);

        return $productTransfer;
    }
}
