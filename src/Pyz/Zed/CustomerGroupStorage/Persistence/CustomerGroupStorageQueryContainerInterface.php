<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Persistence;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\CustomerGroupStorage\Persistence\PyzCustomerGroupStorageQuery;

interface CustomerGroupStorageQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $customerGroupIds
     *
     * @return \Orm\Zed\CustomerGroupStorage\Persistence\PyzCustomerGroupStorageQuery
     */
    public function queryCustomerGroupStorageByCustomerGroupIds(array $customerGroupIds): PyzCustomerGroupStorageQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $customerGroupIds
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroupByIds(array $customerGroupIds): SpyCustomerGroupQuery;
}
