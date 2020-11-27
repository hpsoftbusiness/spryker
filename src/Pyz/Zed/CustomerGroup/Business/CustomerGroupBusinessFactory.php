<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business;

use Pyz\Zed\CustomerGroup\Business\Model\CustomerGroup;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupBusinessFactory as SprykerCustomerGroupBusinessFactory;

/**
 * @method \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 */
class CustomerGroupBusinessFactory extends SprykerCustomerGroupBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerGroup\Business\Model\CustomerGroupInterface
     */
    public function createCustomerGroup()
    {
        return new CustomerGroup($this->getQueryContainer());
    }
}
