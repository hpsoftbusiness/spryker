<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Mapper;

use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapper as SprykerProductPageSearchMapper;

class ProductPageSearchMapper extends SprykerProductPageSearchMapper
{
    /**
     * @param array $productAbstractLocalizedData
     *
     * @return \Generated\Shared\Transfer\ProductPageSearchTransfer
     */
    public function mapToProductPageSearchTransfer(array $productAbstractLocalizedData)
    {
        $productPageSearchTransfer = parent::mapToProductPageSearchTransfer($productAbstractLocalizedData);
        $productPageSearchTransfer->setIsAffiliate($productAbstractLocalizedData['SpyProductAbstract']['is_affiliate'] ?? false);
        $productPageSearchTransfer->setBrand($productAbstractLocalizedData['SpyProductAbstract']['attributes']['brand'] ?? null);

        return $productPageSearchTransfer;
    }
}
