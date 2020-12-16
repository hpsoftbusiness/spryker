<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\NavigationStorage\Business;

use Pyz\Zed\NavigationStorage\Business\Storage\NavigationStorageWriter;
use Pyz\Zed\NavigationStorage\NavigationStorageDependencyProvider;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\NavigationStorage\Business\NavigationStorageBusinessFactory as SprykerNavigationStorageBusinessFactory;
use Spryker\Zed\NavigationStorage\Business\Storage\NavigationStorageWriterInterface;

class NavigationStorageBusinessFactory extends SprykerNavigationStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\NavigationStorage\Business\Storage\NavigationStorageWriterInterface
     */
    public function createNavigationStorageWriter(): NavigationStorageWriterInterface
    {
        return new NavigationStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getNavigationFacade(),
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getUtilEncodingService(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
