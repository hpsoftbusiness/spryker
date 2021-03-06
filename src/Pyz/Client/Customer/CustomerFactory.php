<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Customer;

use Pyz\Client\Customer\Zed\CustomerStub;
use Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Spryker\Client\Customer\CustomerFactory as SprykerCustomerFactory;

/**
 * @method \Pyz\Client\Customer\CustomerConfig getConfig()
 */
class CustomerFactory extends SprykerCustomerFactory
{
    /**
     * @return \Pyz\Client\Customer\Zed\CustomerStub|\Spryker\Client\Customer\Zed\CustomerStub|\Spryker\Client\Customer\Zed\CustomerStubInterface
     */
    public function createZedCustomerStub()
    {
        return new CustomerStub($this->getProvidedDependency(CustomerDependencyProvider::SERVICE_ZED));
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    public function getMyWorldMarketplaceApiClient(): MyWorldMarketplaceApiClientInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::CLIENT_MY_WORLD_MARKETPLACE_API_CLIENT);
    }

    /**
     * @return \Pyz\Client\CustomerGroupStorage\CustomerGroupStorageClientInterface
     */
    public function getCustomerGroupStorageClient(): CustomerGroupStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::CLIENT_CUSTOMER_GROUP_STORAGE);
    }
}
