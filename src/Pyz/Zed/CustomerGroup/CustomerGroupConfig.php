<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup;

use Spryker\Zed\CustomerGroup\CustomerGroupConfig as SprykerCustomerGroupConfig;

class CustomerGroupConfig extends SprykerCustomerGroupConfig
{
    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_TYPE_CUSTOMER
     */
    protected const CUSTOMER_TYPE_CUSTOMER = 'Customer';

    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_EMPLOYEE
     */
    protected const CUSTOMER_TYPE_EMPLOYEE = 'Employee';

    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_MARKETER
     */
    protected const CUSTOMER_TYPE_MARKETER = 'Marketer';

    public function getCustomerTypeToCustomerGroupNameMap(): array
    {
        return [
            static::CUSTOMER_TYPE_CUSTOMER => 'Customer_mW',
            static::CUSTOMER_TYPE_EMPLOYEE => 'Employee',
            static::CUSTOMER_TYPE_MARKETER => 'Marketers',
        ];
    }
}
