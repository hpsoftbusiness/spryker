<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;

class CombinedProductImageMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [];
    }
}
