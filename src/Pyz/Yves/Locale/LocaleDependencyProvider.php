<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale;

use Pyz\Yves\Locale\Plugin\Locale\LocaleLocalePlugin;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Locale\LocaleDependencyProvider as SprykerLocaleDependencyProvider;

class LocaleDependencyProvider extends SprykerLocaleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @return \Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface
     */
    public function getLocalePlugin(): LocalePluginInterface
    {
        return new LocaleLocalePlugin();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    private function addCustomerClient(Container $container): void
    {
        $container->set(self::CLIENT_CUSTOMER, static function (Container $container) {
            return $container->getLocator()->customer()->client();
        });
    }
}
