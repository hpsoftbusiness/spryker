<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Persistence;

use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainer as SprykerCustomerGroupQueryContainer;

/**
 * @method \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory getFactory()
 */
class CustomerGroupQueryContainer extends SprykerCustomerGroupQueryContainer implements CustomerGroupQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery
     */
    public function queryCustomerGroupToProductListByFkCustomerGroup(
        int $idCustomerGroup
    ): PyzCustomerGroupToProductListQuery {
        return $this->getFactory()
            ->getCustomerGroupToProductListQuery()
            ->filterByFkCustomerGroup($idCustomerGroup);
    }
}
