<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class MyWorldPaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MY_WORLD_PAYMENT = 'CLIENT_MY_WORLD_PAYMENT';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const SERVICE_TRANSLATOR = 'translator';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMyWorldPaymentClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addSessionClient($container);
        $container = $this->addTranslatorService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMyWorldPaymentClient(Container $container): Container
    {
        $container->set(static::CLIENT_MY_WORLD_PAYMENT, function (Container $container) {
            return $container->getLocator()->myWorldPayment()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return $container->getLocator()->cart()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container->set(static::CLIENT_SESSION, $container->getLocator()->session()->client());

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function addTranslatorService(Container $container): Container
    {
        $container->set(static::SERVICE_TRANSLATOR, $container->getApplicationService(static::SERVICE_TRANSLATOR));

        return $container;
    }
}
