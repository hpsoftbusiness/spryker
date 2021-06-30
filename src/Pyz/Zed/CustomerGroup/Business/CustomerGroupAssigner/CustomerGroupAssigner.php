<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business\CustomerGroupAssigner;

use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
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
     * @return void
     */
    public function assignCustomerToDefaultGroups(CustomerTransfer $customerTransfer): void
    {
        $customerTransfer->requireIdCustomer();
        $customerGroups = $this->getCustomerGroupFromCustomerTransfer($customerTransfer);

        foreach ($customerGroups as $customerGroupTransfer) {
            $customerGroupToCustomerAssignmentTransfer = new CustomerGroupToCustomerAssignmentTransfer();
            $customerGroupToCustomerAssignmentTransfer
                ->setIdCustomerGroup(
                    $customerGroupTransfer->getIdCustomerGroup()
                )
                ->addIdCustomerToAssign(
                    $customerTransfer->getIdCustomer()
                );
            $customerGroupTransfer->setCustomerAssignment($customerGroupToCustomerAssignmentTransfer);
            $this->customerGroup->update($customerGroupTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer[]
     */
    protected function getCustomerGroupFromCustomerTransfer(CustomerTransfer $customerTransfer): array
    {
        $customerGroups = [];
        if ($customerTransfer->getIsEliteClub()) {
            $customerGroupEliteClub = $this
                ->customerGroupRepository
                ->findCustomerGroupByName(
                    CustomerGroupConfig::CUSTOMER_GROUP_NAME_ELITE_CLUB
                );

            if ($customerGroupEliteClub !== null) {
                $customerGroups[] = $customerGroupEliteClub;
            }
        }

        if ($customerTransfer->getCustomerType()) {
            $customerTypeToCustomerGroupNameMap = $this
                ->customerGroupConfig
                ->getCustomerTypeToCustomerGroupNameMap();

            $customerGroupName = $customerTypeToCustomerGroupNameMap[$customerTransfer->getCustomerType()] ?? null;
            if ($customerGroupName !== null) {
                $customerGroups[] = $this
                    ->customerGroupRepository
                    ->findCustomerGroupByName(
                        $customerGroupName
                    );
            }
        }

        return $customerGroups;
    }
}
