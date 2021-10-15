<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Pyz\Client\Weclapp\WeclappClientInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

class CustomerExporter implements CustomerExporterInterface
{
    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        CustomerFacadeInterface $customerFacade
    ) {
        $this->weclappClient = $weclappClient;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param array $customersIds
     * @param bool $exportNotExisting
     *
     * @return void
     */
    public function exportCustomers(array $customersIds, bool $exportNotExisting): void
    {
        $customerCollection = $this->getCustomerCollection($customersIds);

        foreach ($customerCollection->getCustomers() as $customer) {
            $weclappCustomer = $this->weclappClient->getCustomer($customer);
            if ($weclappCustomer) {
                $this->weclappClient->putCustomer($customer, $weclappCustomer);
            } elseif ($exportNotExisting) {
                $this->weclappClient->postCustomer($customer);
            }
        }
    }

    /**
     * @param array $customersIds
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    protected function getCustomerCollection(array $customersIds): CustomerCollectionTransfer
    {
        $customerCollection = $this->customerFacade->getCustomerCollectionByCriteria(
            (new CustomerCriteriaFilterTransfer())->setIdCustomers($customersIds)
        );
        foreach ($customerCollection->getCustomers() as $customer) {
            $customer->setAddresses($this->customerFacade->getAddresses($customer));
        }

        return $customerCollection;
    }
}
