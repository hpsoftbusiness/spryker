<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi;

use Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander\CategoryExpanderPlugin;
use Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander\DescriptionExpanderPlugin;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Shared\Kernel\Store;

class ProductFeedRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_FEED = 'CLIENT_PRODUCT_FEED';
    public const CLIENT_MONEY = 'CLIENT_MONEY';
    public const STORE = 'STORE';
    public const PLUGINS_READER_EXPANDER = 'PLUGINS_READER_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductFeed($container);
        $container = $this->addStore($container);
        $container = $this->addReaderExpanderPlugins($container);
        $container = $this->addMoneyClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductFeed(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_FEED, function (Container $container) {
            return $container->getLocator()->productFeed()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(self::STORE, $this->getStore());

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addReaderExpanderPlugins(Container $container): Container
    {
        $container->set(self::PLUGINS_READER_EXPANDER, function (Container $container) {
            return $this->getReaderExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addMoneyClient(Container $container): Container
    {
        $container->set(static::CLIENT_MONEY, $container->getLocator()->money()->client());

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore(): Store
    {
        return Store::getInstance();
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Pyz\Glue\ProductFeedRestApi\Processor\Reader\ReaderExpander\ReaderExpanderInterface[]
     */
    protected function getReaderExpanderPlugins(Container $container): array
    {
        return [
            new DescriptionExpanderPlugin(
                $container->getLocator()->productStorage()->client(),
                $this->getStore()
            ),
            new CategoryExpanderPlugin(
                $container->getLocator()->productCategoryStorage()->client(),
                $this->getStore()
            ),
        ];
    }
}
