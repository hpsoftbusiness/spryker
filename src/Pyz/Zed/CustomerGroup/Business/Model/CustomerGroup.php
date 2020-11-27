<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business\Model;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Orm\Zed\CustomerGroupProductList\Persistence\SpyCustomerGroupToProductList;
use Spryker\Zed\CustomerGroup\Business\Model\CustomerGroup as SprykerCustomerGroup;

class CustomerGroup extends SprykerCustomerGroup
{
    /**
     * @var \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    protected function executeAddTransaction(CustomerGroupTransfer $customerGroupTransfer): CustomerGroupTransfer
    {
        $customerGroupEntity = new SpyCustomerGroup();
        $customerGroupEntity->fromArray($customerGroupTransfer->toArray());

        $customerGroupEntity->save();
        $customerGroupTransfer = $this->mapEntityToTransfer($customerGroupEntity, $customerGroupTransfer);

        $this->saveCustomers($customerGroupTransfer, $customerGroupEntity);

        $this->saveProductLists($customerGroupTransfer, $customerGroupEntity);

        return $customerGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    protected function executeUpdateTransaction(CustomerGroupTransfer $customerGroupTransfer): void
    {
        $customerGroupEntity = $this->getCustomerGroup($customerGroupTransfer);
        $customerGroupEntity->fromArray($customerGroupTransfer->toArray());

        $customerGroupEntity->save();

        $customerGroupTransfer = $this->mapEntityToTransfer($customerGroupEntity, $customerGroupTransfer);

        $this->removeCustomersFromGroup($customerGroupTransfer);
        $this->saveCustomers($customerGroupTransfer, $customerGroupEntity);

        $this->removeProductListsFromGroup($customerGroupTransfer);
        $this->saveProductLists($customerGroupTransfer, $customerGroupEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function removeProductListsFromGroup(CustomerGroupTransfer $customerGroupTransfer): void
    {
        $customerGroupTransfer
            ->requireIdCustomerGroup();

        $idsProductListToDeAssign = $customerGroupTransfer->getProductListAssignment() ?
            $customerGroupTransfer->getProductListAssignment()->getIdsProductListToDeAssign() :
            [];

        foreach ($idsProductListToDeAssign as $idProductListToDeAssign) {
            $productListEntity = $this->queryContainer
                ->queryCustomerGroupToProductListByFkCustomerGroup($customerGroupTransfer->getIdCustomerGroup())
                ->filterByFkProductList($idProductListToDeAssign)
                ->findOne();

            if (!$productListEntity) {
                continue;
            }

            $productListEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup $customerGroupEntity
     *
     * @return void
     */
    protected function saveProductLists(
        CustomerGroupTransfer $customerGroupTransfer,
        SpyCustomerGroup $customerGroupEntity
    ): void {
        $idsProductListToAssign = $customerGroupTransfer->getProductListAssignment() ?
            $customerGroupTransfer->getProductListAssignment()->getIdsProductListToAssign() :
            [];

        foreach ($idsProductListToAssign as $idProductListToAssign) {
            $customerGroupToCustomerEntity = new SpyCustomerGroupToProductList();
            $customerGroupToCustomerEntity->setFkCustomerGroup($customerGroupEntity->getIdCustomerGroup());
            $customerGroupToCustomerEntity->setFkProductList($idProductListToAssign);
            $customerGroupToCustomerEntity->save();
        }
    }
}
