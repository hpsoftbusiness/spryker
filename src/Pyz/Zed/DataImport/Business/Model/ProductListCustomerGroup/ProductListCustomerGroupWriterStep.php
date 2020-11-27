<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductListCustomerGroup;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\CustomerGroupProductList\Persistence\SpyCustomerGroupToProductListQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductListCustomerGroupWriterStep implements DataImportStepInterface
{
    public const COL_PRODUCT_LIST_KEY = 'product_list_key';
    public const COL_CUSTOMER_GROUP_NAME = 'customer_group_name';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $productListEntity = SpyProductListQuery::create()
            ->findOneByKey($dataSet[static::COL_PRODUCT_LIST_KEY]);

        if (!$productListEntity) {
            throw new EntityNotFoundException(
                sprintf('Product list with key "%s" not found.', $dataSet[static::COL_PRODUCT_LIST_KEY])
            );
        }

        $customerGroupEntity = SpyCustomerGroupQuery::create()
            ->findOneByName($dataSet[static::COL_CUSTOMER_GROUP_NAME]);

        if (!$customerGroupEntity) {
            throw new EntityNotFoundException(
                sprintf('Customer group with name "%s" not found.', $dataSet[static::COL_CUSTOMER_GROUP_NAME])
            );
        }

        $customerGroupToProductListEntity = SpyCustomerGroupToProductListQuery::create()
            ->filterByFkCustomerGroup($customerGroupEntity->getIdCustomerGroup())
            ->filterByFkProductList($productListEntity->getIdProductList())
            ->findOneOrCreate();

        if ($customerGroupToProductListEntity->isNew()) {
            $customerGroupToProductListEntity->save();
        }
    }
}
