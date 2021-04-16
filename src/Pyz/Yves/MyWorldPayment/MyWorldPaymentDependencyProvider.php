<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class MyWorldPaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MY_WORLD_PAYMENT_CLIENT = 'MY_WORLD_PAYMENT_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMyWorldPaymentClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMyWorldPaymentClient(Container $container): Container
    {
        $container->set(static::MY_WORLD_PAYMENT_CLIENT, function (Container $container) {
            return $container->getLocator()->myWorldPayment()->client();
        });

        return $container;
    }
}
