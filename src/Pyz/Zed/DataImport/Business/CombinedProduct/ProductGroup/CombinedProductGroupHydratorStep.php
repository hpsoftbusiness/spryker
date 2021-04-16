<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductGroup;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CombinedProductGroupHydratorStep implements DataImportStepInterface
{
    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';
    public const COLUMN_PRODUCT_GROUP_KEY = 'product_group.group_key';
    public const COLUMN_POSITION = 'product_group.position';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
    }
}
