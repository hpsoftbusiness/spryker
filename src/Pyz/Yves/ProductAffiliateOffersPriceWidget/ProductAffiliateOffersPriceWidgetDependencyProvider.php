<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersPriceWidget;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductAffiliateOffersPriceWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT = 'MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT';
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMerchantProductOfferStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
