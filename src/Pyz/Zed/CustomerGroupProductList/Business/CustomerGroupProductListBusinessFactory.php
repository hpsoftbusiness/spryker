<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Business;

use Pyz\Zed\CustomerGroupProductList\Business\Expander\CustomerExpander;
use Pyz\Zed\CustomerGroupProductList\Business\Expander\CustomerExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListRepositoryInterface getRepository()
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\CustomerGroupProductList\CustomerGroupProductListConfig getConfig()
 */
class CustomerGroupProductListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\CustomerGroupProductList\Business\Expander\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander($this->getRepository());
    }
}
