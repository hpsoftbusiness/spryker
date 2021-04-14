<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ContentProductWidget;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ContentProductWidget\ContentProductWidgetDependencyProvider as SprykerContentProductWidgetDependencyProvider;

class ContentProductWidgetDependencyProvider extends SprykerContentProductWidgetDependencyProvider
{
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
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
