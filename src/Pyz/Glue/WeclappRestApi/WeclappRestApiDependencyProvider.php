<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class WeclappRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUEUE = 'CLIENT_QUEUE';
    public const CLIENT_API_LOG = 'CLIENT_API_LOG';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQueueClient($container);
        $container = $this->addApiLogClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQueueClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUEUE, function (Container $container) {
            return $container->getLocator()->queue()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addApiLogClient(Container $container): Container
    {
        $container->set(static::CLIENT_API_LOG, function (Container $container) {
            return $container->getLocator()->apiLog()->client();
        });

        return $container;
    }
}
