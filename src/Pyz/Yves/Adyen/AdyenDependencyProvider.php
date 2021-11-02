<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen;

use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\Adyen\AdyenDependencyProvider as SprykerAdyenDependencyProvider;

class AdyenDependencyProvider extends SprykerAdyenDependencyProvider
{
    public const CLIENT_MYWORLD_PAYMENT = 'CLIENT_MYWORLD_PAYMENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $this->addMyWorldPaymentClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    private function addMyWorldPaymentClient(Container $container): Container
    {
        $container->set(self::CLIENT_MYWORLD_PAYMENT, static function (Container $container) {
            return $container->getLocator()->myWorldPayment()->client();
        });

        return $container;
    }
}
