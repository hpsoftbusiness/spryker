<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment;

use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class MyWorldPaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const MY_WORLD_MARKETPLACE_API_CLIENT = 'MY_WORLD_MARKETPLACE_API_CLIENT';
    public const MY_WORLD_PAYMENT_API_FACADE = 'MY_WORLD_PAYMENT_API_FACADE';
    public const SEQUENCE_FACADE = 'SEQUENCE_FACADE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const LOCALE_CLIENT = 'LOCALE_CLIENT';
    public const CUSTOMER_GROUP_QUERY = 'CUSTOMER_GROUP_QUERY';
    public const SERVICE_UTIL_POLLING = 'SERVICE_UTIL_POLLING';
    public const DECIMAL_TO_INTEGER_CONVERTER = 'DECIMAL_TO_INTEGER_CONVERTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $this->addUtilPollingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMyWorldMarketplaceApiClient($container);
        $container = $this->addMyWorldPaymentFacade($container);
        $container = $this->addSequenceFacade($container);
        $container = $this->addCustomerGroup($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addLocaleClient($container);
        $this->addDecimalToIntegerConverter($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMyWorldMarketplaceApiClient(Container $container): Container
    {
        $container->set(static::MY_WORLD_MARKETPLACE_API_CLIENT, function (Container $container) {
            return $container->getLocator()->myWorldMarketplaceApi()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMyWorldPaymentFacade(Container $container): Container
    {
        $container->set(static::MY_WORLD_PAYMENT_API_FACADE, function (Container $container) {
            return $container->getLocator()->myWorldPaymentApi()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSequenceFacade(Container $container): Container
    {
        $container->set(static::SEQUENCE_FACADE, function (Container $container) {
            return $container->getLocator()->sequenceNumber()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerGroup(Container $container): Container
    {
        $container->set(static::CUSTOMER_GROUP_QUERY, function (Container $container) {
            return $container->getLocator()->customerGroup()->queryContainer();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return $container->getLocator()->productStorage()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::LOCALE_CLIENT, function (Container $container) {
            return $container->getLocator()->locale()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addUtilPollingService(Container $container): void
    {
        $container->set(self::SERVICE_UTIL_POLLING, static function (Container $container) {
            return $container->getLocator()->utilPolling()->service();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addDecimalToIntegerConverter(Container $container): void
    {
        $container->set(self::DECIMAL_TO_INTEGER_CONVERTER, static function (Container $container) {
            return new DecimalToIntegerConverter();
        });
    }
}
