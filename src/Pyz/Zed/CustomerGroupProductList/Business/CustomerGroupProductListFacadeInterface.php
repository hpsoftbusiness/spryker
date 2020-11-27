<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Business;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerGroupProductListFacadeInterface
{
    /**
     * Specification:
     * - Finds product lists by company business unit.
     * - Expands customer transfer with CustomerProductListCollectionTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithProductListIds(CustomerTransfer $customerTransfer): CustomerTransfer;
}
