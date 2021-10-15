<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\ErrorHandler\ErrorLogger;

class WeclappDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_HTTP = 'CLIENT_HTTP';
    public const CLIENT_API_LOG = 'CLIENT_API_LOG';
    public const ERROR_LOGGER = 'ERROR_LOGGER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addHttpClient($container);
        $container = $this->addErrorLogger($container);
        $container = $this->addApiLogClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHttpClient(Container $container): Container
    {
        $container->set(static::CLIENT_HTTP, function () {
            return new Client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addErrorLogger(Container $container): Container
    {
        $container->set(static::ERROR_LOGGER, function () {
            return ErrorLogger::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addApiLogClient(Container $container): Container
    {
        $container->set(static::CLIENT_API_LOG, function (Container $container) {
            return $container->getLocator()->apiLog()->client();
        });

        return $container;
    }
}
