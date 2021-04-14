<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Currency;

use Spryker\Yves\Currency\CurrencyDependencyProvider as SprykerCurrencyDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CurrencyDependencyProvider extends SprykerCurrencyDependencyProvider
{
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return void
     */
    private function addLocaleClient(Container $container): void
    {
        $container->set(self::CLIENT_LOCALE, static function (Container $container) {
            return $container->getLocator()->locale()->client();
        });
    }
}
