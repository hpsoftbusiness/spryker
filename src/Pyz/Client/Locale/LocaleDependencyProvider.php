<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale;

use Spryker\Client\Kernel\Container;
use Spryker\Client\Locale\LocaleDependencyProvider as SprykerLocaleDependencyProvider;
use Spryker\Shared\Kernel\Store;

class LocaleDependencyProvider extends SprykerLocaleDependencyProvider
{
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    private function addStore(Container $container): void
    {
        $container->set(self::STORE, static function () {
            return Store::getInstance();
        });
    }
}
