<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\ErrorHandler\ErrorLogger;

class SsoDependencyProvider extends AbstractDependencyProvider
{
    public const ERROR_LOGGER = 'ERROR_LOGGER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addErrorLogger($container);

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
}
