<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
