<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport;

use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;

class PostingExportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_SALES_ORDER_UID = 'FACADE_SALES_ORDER_UID';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addSalesFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addSalesOrderUidFacade($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container): SalesFacadeInterface {
            return $container->getLocator()->sales()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container): MoneyFacadeInterface {
            return $container->getLocator()->money()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container): LocaleFacadeInterface {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderUidFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES_ORDER_UID, function (Container $container) {
            return $container->getLocator()->salesOrderUid()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container): StoreClientInterface {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }
}
