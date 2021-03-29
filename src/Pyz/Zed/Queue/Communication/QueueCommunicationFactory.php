<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Communication;

use Pyz\Zed\ProductDataImport\ProductDataImportDependencyProvider;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Zed\Queue\Communication\QueueCommunicationFactory as SprykerQueueCommunicationFactory;

/**
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Queue\QueueConfig getConfig()
 * @method \Spryker\Zed\Queue\Business\QueueFacadeInterface getFacade()
 */
class QueueCommunicationFactory extends SprykerQueueCommunicationFactory
{
    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(ProductDataImportDependencyProvider::CLIENT_STORAGE);
    }
}
