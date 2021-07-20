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
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const SERVICE_CUSTOMER = 'SERVICE_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $this->addCustomerClient($container);
        $this->addCustomerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    private function addCustomerClient(Container $container): Container
    {
        $container->set(self::CLIENT_CUSTOMER, static function (Container $container) {
            return $container->getLocator()->customer()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    private function addCustomerService(Container $container): Container
    {
        $container->set(self::SERVICE_CUSTOMER, static function (Container $container) {
            return $container->getLocator()->customer()->service();
        });

        return $container;
    }
}
