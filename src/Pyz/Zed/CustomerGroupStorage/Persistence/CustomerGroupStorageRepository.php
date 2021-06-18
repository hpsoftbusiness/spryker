<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Persistence;

use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Orm\Zed\CustomerGroupProductList\Persistence\Map\PyzCustomerGroupToProductListTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStoragePersistenceFactory getFactory()
 */
class CustomerGroupStorageRepository extends AbstractRepository implements CustomerGroupStorageRepositoryInterface
{
    /**
     * @param int $idCustomerGroup
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function findCustomerGroupByIdCustomerGroup(int $idCustomerGroup): CustomerGroupTransfer
    {
        $data = $this->getFactory()
            ->createCustomerGroupQuery()
            ->select([
                SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP,
                SpyCustomerGroupTableMap::COL_NAME,
                SpyCustomerGroupTableMap::COL_DESCRIPTION,
            ])
            ->withColumn(
                'GROUP_CONCAT(' . PyzCustomerGroupToProductListTableMap::COL_FK_PRODUCT_LIST . ')',
                CustomerGroupTransfer::PRODUCT_LISTS
            )
            ->filterByIdCustomerGroup($idCustomerGroup)
            ->leftJoinPyzCustomerGroupToProductList()
            ->groupByIdCustomerGroup()
            ->findOne();

        $customerGroupTransfer = (new CustomerGroupTransfer())
            ->setIdCustomerGroup($data[SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP])
            ->setName($data[SpyCustomerGroupTableMap::COL_NAME])
            ->setDescription($data[SpyCustomerGroupTableMap::COL_DESCRIPTION]);

        $productListIds = explode(',', $data[CustomerGroupTransfer::PRODUCT_LISTS]);
        foreach ($productListIds as $productListId) {
            $productList = (new CustomerGroupToProductListTransfer())
                ->setIdCustomerGroup($data[SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP])
                ->setIdProductList((int)$productListId);

            $customerGroupTransfer->addProductList($productList);
        }

        return $customerGroupTransfer;
    }
}
