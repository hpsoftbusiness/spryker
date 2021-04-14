<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\GlossaryStorage;

use Spryker\Client\GlossaryStorage\GlossaryStorageDependencyProvider as SprykerGlossaryStorageDependencyProvider;
use Spryker\Client\Kernel\Container;

class GlossaryStorageDependencyProvider extends SprykerGlossaryStorageDependencyProvider
{
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    private function addStoreClient(Container $container): void
    {
        $container->set(self::CLIENT_STORE, static function (Container $container) {
            return $container->getLocator()->store()->client();
        });
    }
}
