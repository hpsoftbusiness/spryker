<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Persistence;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStoragePersistenceFactory getFactory()
 */
class CustomerGroupStorageEntityManager extends AbstractEntityManager implements CustomerGroupStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function saveCustomerGroupStorage(CustomerGroupTransfer $customerGroupTransfer): void
    {
        $customerGroupStorage = $this
            ->getFactory()
            ->createCustomerGroupStorageQuery()
            ->filterByFkCustomerGroup(
                $customerGroupTransfer->getIdCustomerGroup()
            )
            ->findOneOrCreate();

        $customerGroupStorage
            ->setFkCustomerGroup(
                $customerGroupTransfer->getIdCustomerGroup()
            )
            ->setData(
                $customerGroupTransfer->toArray()
            )
            ->save();
    }

    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    public function deleteCustomerGroupStorageByIdCustomerGroup(int $idCustomerGroup): void
    {
        $this
            ->getFactory()
            ->createCustomerGroupStorageQuery()
            ->filterByFkCustomerGroup($idCustomerGroup)
            ->delete();
    }
}
