<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageRepositoryInterface getRepository()
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\CustomerGroupStorage\Business\CustomerGroupStorageBusinessFactory getFactory()
 */
class CustomerGroupStorageFacade extends AbstractFacade implements CustomerGroupStorageFacadeInterface
{
    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    public function publish(int $idCustomerGroup): void
    {
        $customerGroupTransfer = $this
            ->getFactory()
            ->createCustomerGroupReader()
            ->findCustomerGroupById($idCustomerGroup);

        $this
            ->getEntityManager()
            ->saveCustomerGroupStorage($customerGroupTransfer);
    }

    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    public function unpublish(int $idCustomerGroup): void
    {
        $this
            ->getEntityManager()
            ->deleteCustomerGroupStorageByIdCustomerGroup($idCustomerGroup);
    }
}
