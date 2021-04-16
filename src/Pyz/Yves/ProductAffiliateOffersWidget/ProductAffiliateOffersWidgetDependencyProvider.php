<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class ProductAffiliateOffersWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT = 'MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMerchantProductOfferStorageClient($container);
        $container = $this->addProductStorageClient($container);

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
}
