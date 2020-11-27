<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Table\Assignment;

use Orm\Zed\CustomerGroupProductList\Persistence\Map\SpyCustomerGroupToProductListTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class AssignmentProductListQueryBuilder implements AssignmentProductListQueryBuilderInterface
{
    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function buildNotAssignedQuery($idCustomerGroup = null): SpyProductListQuery
    {
        $query = (new SpyProductListQuery());

        if ($idCustomerGroup) {
            $query->addJoin(
                [SpyProductListTableMap::COL_ID_PRODUCT_LIST, $idCustomerGroup],
                [SpyCustomerGroupToProductListTableMap::COL_FK_PRODUCT_LIST, SpyCustomerGroupToProductListTableMap::COL_FK_CUSTOMER_GROUP],
                Criteria::LEFT_JOIN
            )
                ->addAnd(SpyCustomerGroupToProductListTableMap::COL_FK_CUSTOMER_GROUP, null, Criteria::ISNULL);
        }

        return $query;
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function buildAssignedQuery($idCustomerGroup = null): SpyProductListQuery
    {
        $query = (new SpyProductListQuery())
            ->useSpyCustomerGroupToProductListQuery()
                ->filterByFkCustomerGroup($idCustomerGroup)
            ->endUse();

        return $query;
    }
}
