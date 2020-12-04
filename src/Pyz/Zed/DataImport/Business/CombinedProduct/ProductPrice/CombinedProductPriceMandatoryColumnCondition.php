<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;

class CombinedProductPriceMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [];
    }
}
