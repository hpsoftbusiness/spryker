<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock;

use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CombinedProductStockTypeDataSetCondition implements DataSetConditionInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    public function hasData(DataSetInterface $dataSet): bool
    {
        if (!empty($dataSet[CombinedProductStockHydratorStep::COLUMN_CONCRETE_SKU])) {
            return true;
        }

        return false;
    }
}
