<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class MyWorldPaymentDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    public const SERVICE_CUSTOMER = 'SERVICE_CUSTOMER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addZedRequestClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCustomerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequestClient(Container $container)
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return $container->getLocator()->zedRequest()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    private function addCustomerClient(Container $container): Container
    {
        $container->set(self::CLIENT_CUSTOMER, static function (Container $container) {

            return $container->getLocator()->customer()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    private function addCustomerService(Container $container): Container
    {
        $container->set(self::SERVICE_CUSTOMER, static function (Container $container) {

            return $container->getLocator()->customer()->service();
        });

        return $container;
    }
}
