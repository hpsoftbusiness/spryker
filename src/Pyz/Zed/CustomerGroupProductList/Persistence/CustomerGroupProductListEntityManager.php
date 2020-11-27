<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Persistence;

use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;
use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListPersistenceFactory getFactory()
 */
class CustomerGroupProductListEntityManager extends AbstractEntityManager implements CustomerGroupProductListEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupToProductListTransfer
     */
    public function createCustomerGroupProductList(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): CustomerGroupToProductListTransfer {
        $customerGroupProductListMapper = $this->getFactory()->createCustomerGroupProductListMapper();

        $customerGroupToProductListEntity = new PyzCustomerGroupToProductList();
        $customerGroupToProductListEntity = $customerGroupProductListMapper
            ->mapCustomerGroupProductListTransferToCustomerGroupProductListEntity(
                $customerGroupToProductListTransfer,
                $customerGroupToProductListEntity
            );

        $customerGroupToProductListEntity->save();

        return $customerGroupProductListMapper
            ->mapCustomerGroupProductListEntityToCustomerGroupProductListTransfer(
                $customerGroupToProductListEntity,
                $customerGroupToProductListTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return void
     */
    public function deleteCustomerGroupProductList(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): void {
        $customerGroupToProductListEntity = $this->getFactory()
            ->createCustomerGroupToProductListQuery()
            ->filterByFkCustomerGroup($customerGroupToProductListTransfer->getIdCustomerGroup())
            ->filterByFkProductList($customerGroupToProductListTransfer->getIdProductList())
            ->findOne();

        if (!$customerGroupToProductListEntity) {
            return;
        }

        $customerGroupToProductListEntity->delete();
    }
}
