<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\CustomerGroup;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CustomerGroupWriterStep implements DataImportStepInterface
{
    protected const COL_NAME = 'name';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $customerGroupEntity = SpyCustomerGroupQuery::create()
            ->filterByName($dataSet[static::COL_NAME])
            ->findOneOrCreate();

        $customerGroupEntity->fromArray($dataSet->getArrayCopy());
        $customerGroupEntity->save();
    }
}
