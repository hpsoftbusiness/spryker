<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Persistence;

use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;

interface CustomerGroupProductListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupToProductListTransfer
     */
    public function createCustomerGroupProductList(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): CustomerGroupToProductListTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return void
     */
    public function deleteCustomerGroupProductList(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): void;
}
