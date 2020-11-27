<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Persistence;

use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface as SprykerCustomerGroupQueryContainerInterface;

interface CustomerGroupQueryContainerInterface extends SprykerCustomerGroupQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery
     */
    public function queryCustomerGroupToProductListByFkCustomerGroup(
        int $idCustomerGroup
    ): PyzCustomerGroupToProductListQuery;
}
