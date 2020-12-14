<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business;

use Pyz\Zed\CustomerGroup\Business\CustomerGroupAssigner\CustomerGroupAssigner;
use Pyz\Zed\CustomerGroup\Business\CustomerGroupAssigner\CustomerGroupAssignerInterface;
use Pyz\Zed\CustomerGroup\Business\Model\CustomerGroup;
use Pyz\Zed\CustomerGroup\CustomerGroupDependencyProvider;
use Pyz\Zed\CustomerGroupProductList\Business\CustomerGroupProductListFacadeInterface;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupBusinessFactory as SprykerCustomerGroupBusinessFactory;

/**
 * @method \Pyz\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 * @method \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface getRepository()
 */
class CustomerGroupBusinessFactory extends SprykerCustomerGroupBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerGroup\Business\Model\CustomerGroupInterface
     */
    public function createCustomerGroup()
    {
        return new CustomerGroup(
            $this->getQueryContainer(),
            $this->getCustomerGroupProductListFacade()
        );
    }

    /**
     * @return \Pyz\Zed\CustomerGroupProductList\Business\CustomerGroupProductListFacadeInterface
     */
    public function getCustomerGroupProductListFacade(): CustomerGroupProductListFacadeInterface
    {
        return $this->getProvidedDependency(CustomerGroupDependencyProvider::FACADE_CUSTOMER_GROUP_PRODUCT_LIST);
    }

    /**
     * @return \Pyz\Zed\CustomerGroup\Business\CustomerGroupAssigner\CustomerGroupAssignerInterface
     */
    public function createCustomerGroupAssigner(): CustomerGroupAssignerInterface
    {
        return new CustomerGroupAssigner(
            $this->createCustomerGroup(),
            $this->getRepository(),
            $this->getConfig()
        );
    }
}
