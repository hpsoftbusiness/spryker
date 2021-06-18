<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Persistence;

use Generated\Shared\Transfer\CustomerGroupTransfer;

interface CustomerGroupStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function saveCustomerGroupStorage(CustomerGroupTransfer $customerGroupTransfer): void;

    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    public function deleteCustomerGroupStorageByIdCustomerGroup(int $idCustomerGroup): void;
}
