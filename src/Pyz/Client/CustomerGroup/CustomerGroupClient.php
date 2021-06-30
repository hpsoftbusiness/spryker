<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CustomerGroup;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\CustomerGroup\CustomerGroupFactory getFactory()
 */
class CustomerGroupClient extends AbstractClient implements CustomerGroupClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function reassignCustomerGroups(CustomerTransfer $customerTransfer): void
    {
        $this
            ->getFactory()
            ->createZedCustomerGroupStub()
            ->reassignCustomerGroups($customerTransfer);
    }
}
