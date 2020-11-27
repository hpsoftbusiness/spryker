<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Table\Assignment;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;

interface AssignmentProductListQueryBuilderInterface
{
    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function buildNotAssignedQuery($idCustomerGroup = null): SpyProductListQuery;

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function buildAssignedQuery($idCustomerGroup = null): SpyProductListQuery;
}
