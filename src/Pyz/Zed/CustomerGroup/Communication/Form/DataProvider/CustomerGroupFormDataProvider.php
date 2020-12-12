<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerGroupToProductListAssignmentTransfer;
use Spryker\Zed\CustomerGroup\Communication\Form\DataProvider\CustomerGroupFormDataProvider as SprykerCustomerGroupFormDataProvider;

class CustomerGroupFormDataProvider extends SprykerCustomerGroupFormDataProvider
{
    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function getData($idCustomerGroup = null)
    {
        $customerGroupTransfer = parent::getData($idCustomerGroup);

        return $customerGroupTransfer
            ->setProductListAssignment(new CustomerGroupToProductListAssignmentTransfer());
    }
}
