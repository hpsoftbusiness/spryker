<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business\Model;

use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Pyz\Zed\CustomerGroupProductList\Business\CustomerGroupProductListFacadeInterface;
use Spryker\Zed\CustomerGroup\Business\Model\CustomerGroup as SprykerCustomerGroup;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;

class CustomerGroup extends SprykerCustomerGroup
{
    /**
     * @var \Pyz\Zed\CustomerGroupProductList\Business\CustomerGroupProductListFacadeInterface
     */
    protected $customerGroupProductListFacade;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $queryContainer
     * @param \Pyz\Zed\CustomerGroupProductList\Business\CustomerGroupProductListFacadeInterface $customerGroupProductListFacade
     */
    public function __construct(
        CustomerGroupQueryContainerInterface $queryContainer,
        CustomerGroupProductListFacadeInterface $customerGroupProductListFacade
    ) {
        parent::__construct($queryContainer);

        $this->customerGroupProductListFacade = $customerGroupProductListFacade;
    }

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

        $this->saveProductLists($customerGroupTransfer);

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
        $this->saveProductLists($customerGroupTransfer);
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
            $customerGroupToProductListTransfer = (new CustomerGroupToProductListTransfer())
                ->setIdCustomerGroup($customerGroupTransfer->getIdCustomerGroup())
                ->setIdProductList($idProductListToDeAssign);

            $this->customerGroupProductListFacade->deleteCustomerGroupProductList(
                $customerGroupToProductListTransfer
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    protected function saveProductLists(CustomerGroupTransfer $customerGroupTransfer): void
    {
        $idsProductListToAssign = $customerGroupTransfer->getProductListAssignment() ?
            $customerGroupTransfer->getProductListAssignment()->getIdsProductListToAssign() :
            [];

        foreach ($idsProductListToAssign as $idProductListToAssign) {
            $customerGroupToProductListTransfer = (new CustomerGroupToProductListTransfer())
                ->setIdCustomerGroup($customerGroupTransfer->getIdCustomerGroup())
                ->setIdProductList($idProductListToAssign);

            $this->customerGroupProductListFacade->createCustomerGroupProductList(
                $customerGroupToProductListTransfer
            );
        }
    }
}
