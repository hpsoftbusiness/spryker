<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CustomerGroup;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerGroupClientInterface
{
    /**
     * Reassigns customer groups
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reassignCustomerGroups(CustomerTransfer $customerTransfer): void;
}
