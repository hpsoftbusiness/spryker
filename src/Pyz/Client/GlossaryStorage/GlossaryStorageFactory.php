<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\GlossaryStorage;

use Pyz\Client\GlossaryStorage\Storage\GlossaryStorageReader;
use Spryker\Client\GlossaryStorage\GlossaryStorageFactory as SprykerGlossaryStorageFactory;
use Spryker\Client\GlossaryStorage\Storage\GlossaryStorageReaderInterface;
use Spryker\Client\Store\StoreClientInterface;

class GlossaryStorageFactory extends SprykerGlossaryStorageFactory
{
    /**
     * @return \Spryker\Client\GlossaryStorage\Storage\GlossaryStorageReaderInterface
     */
    public function createTranslator(): GlossaryStorageReaderInterface
    {
        return new GlossaryStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::CLIENT_STORE);
    }
}
