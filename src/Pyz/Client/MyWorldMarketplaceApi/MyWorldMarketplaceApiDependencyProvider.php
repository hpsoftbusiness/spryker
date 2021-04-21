<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi;

use GuzzleHttp\Client;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\ErrorHandler\ErrorLogger;

class MyWorldMarketplaceApiDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_HTTP = 'CLIENT_HTTP';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const ERROR_LOGGER = 'ERROR_LOGGER';
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addHttpClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addErrorLogger($container);
        $this->addCurrencyClient($container);

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
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    private function addCurrencyClient(Container $container): void
    {
        $container->set(self::CLIENT_CURRENCY, static function (Container $container) {
            return $container->getLocator()->currency()->client();
        });
    }
}
