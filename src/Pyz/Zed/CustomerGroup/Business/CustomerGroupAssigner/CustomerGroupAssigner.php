<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business\CustomerGroupAssigner;

use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Zed\CustomerGroup\CustomerGroupConfig;
use Pyz\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface;
use Spryker\Zed\CustomerGroup\Business\Model\CustomerGroupInterface;

class CustomerGroupAssigner implements CustomerGroupAssignerInterface
{
    /**
     * @var \Spryker\Zed\CustomerGroup\Business\Model\CustomerGroupInterface
     */
    protected $customerGroup;

    /**
     * @var \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface
     */
    protected $customerGroupRepository;

    /**
     * @var \Pyz\Zed\CustomerGroup\CustomerGroupConfig
     */
    private $customerGroupConfig;

    /**
     * @param \Spryker\Zed\CustomerGroup\Business\Model\CustomerGroupInterface $customerGroup
     * @param \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface $customerGroupRepository
     * @param \Pyz\Zed\CustomerGroup\CustomerGroupConfig $customerGroupConfig
     */
    public function __construct(
        CustomerGroupInterface $customerGroup,
        CustomerGroupRepositoryInterface $customerGroupRepository,
        CustomerGroupConfig $customerGroupConfig
    ) {
        $this->customerGroup = $customerGroup;
        $this->customerGroupRepository = $customerGroupRepository;
        $this->customerGroupConfig = $customerGroupConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function assignCustomerToDefaultGroupByCustomerType(CustomerTransfer $customerTransfer): ?CustomerGroupTransfer
    {
        $customerTransfer->requireIdCustomer();
        $customerGroupTransfer = $this->getCustomerGroupByCustomerType($customerTransfer);

        if ($customerGroupTransfer !== null) {
            $customerGroupToCustomerAssignmentTransfer = new CustomerGroupToCustomerAssignmentTransfer();
            $customerGroupToCustomerAssignmentTransfer->setIdCustomerGroup($customerGroupTransfer->getIdCustomerGroup())
                ->addIdCustomerToAssign($customerTransfer->getIdCustomer());

            $customerGroupTransfer->setCustomerAssignment($customerGroupToCustomerAssignmentTransfer);

            $this->customerGroup->update($customerGroupTransfer);
        }

        return $customerGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    protected function getCustomerGroupByCustomerType(CustomerTransfer $customerTransfer): ?CustomerGroupTransfer
    {
        if ($customerTransfer->getCustomerType()) {
            $customerTypeToCustomerGroupNameMap = $this->customerGroupConfig->getCustomerTypeToCustomerGroupNameMap();

            if (!empty($customerTypeToCustomerGroupNameMap[$customerTransfer->getCustomerType()])) {
                return $this->customerGroupRepository->findCustomerGroupByName($customerTypeToCustomerGroupNameMap[$customerTransfer->getCustomerType()]);
            }
        }

        return null;
    }
}
