<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttributeStorage;

use Pyz\Client\ProductAttributeStorage\Mapper\ProductAttributeStorageMapper;
use Pyz\Client\ProductAttributeStorage\Mapper\ProductAttributeStorageMapperInterface;
use Pyz\Client\ProductAttributeStorage\Storage\ProductAttributeStorageReader;
use Pyz\Client\ProductAttributeStorage\Storage\ProductAttributeStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ProductAttributeStorageFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\ProductAttributeStorage\Storage\ProductAttributeStorageReaderInterface
     */
    public function createProductAttributeStorageReader(): ProductAttributeStorageReaderInterface
    {
        return new ProductAttributeStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createProductAttributeStorageMapper()
        );
    }

    /**
     * @return \Pyz\Client\ProductAttributeStorage\Mapper\ProductAttributeStorageMapperInterface
     */
    protected function createProductAttributeStorageMapper(): ProductAttributeStorageMapperInterface
    {
        return new ProductAttributeStorageMapper();
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(ProductAttributeStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductAttributeStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
