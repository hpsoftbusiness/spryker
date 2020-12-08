<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        $productPageSearchTransfer->setBrand($productAbstractLocalizedData['SpyProductAbstract']['brand']);

        return $productPageSearchTransfer;
    }
}
