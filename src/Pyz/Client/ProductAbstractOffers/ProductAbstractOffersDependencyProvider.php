<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAbstractOffers;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\Store;

class ProductAbstractOffersDependencyProvider extends AbstractDependencyProvider
{
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT = 'MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT';
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductStorageClient($container);
        $container = $this->addMerchantProductOfferStorageClient($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMerchantProductOfferStorageClient(Container $container): Container
    {
        $container->set(
            static::MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT,
            function (Container $container) {
                return $container->getLocator()->merchantProductOfferStorage()->client();
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(
            static::PRODUCT_STORAGE_CLIENT,
            function (Container $container) {
                return $container->getLocator()->productStorage()->client();
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    private function addStore(Container $container): Container
    {
        $container->set(
            static::STORE,
            function () {
                return Store::getInstance();
            }
        );

        return $container;
    }
}
