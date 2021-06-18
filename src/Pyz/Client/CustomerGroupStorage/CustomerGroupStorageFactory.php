<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CustomerGroupStorage;

use Pyz\Client\CustomerGroupStorage\Storage\CustomerGroupStorageReader;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;

class CustomerGroupStorageFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\CustomerGroupStorage\Storage\CustomerGroupStorageReader
     */
    public function createCustomerGroupStorageReader(): CustomerGroupStorageReader
    {
        return new CustomerGroupStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(CustomerGroupStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CustomerGroupStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
