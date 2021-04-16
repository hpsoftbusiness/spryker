<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersPriceWidget;

use Pyz\Yves\ProductAffiliateOffersWidget\DataProvider\AffiliateDataProvider;
use Pyz\Yves\ProductAffiliateOffersWidget\DataProvider\AffiliateDataProviderInterface;
use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClient;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class ProductAffiliateOffersPriceWidgetFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Yves\ProductAffiliateOffersWidget\DataProvider\AffiliateDataProviderInterface
     */
    public function createAffiliateDataProvider(): AffiliateDataProviderInterface
    {
        return new AffiliateDataProvider();
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAffiliateOffersPriceWidgetDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClient
     */
    public function getMerchantProductOfferStorageClient(): MerchantProductOfferStorageClient
    {
        return $this->getProvidedDependency(
            ProductAffiliateOffersPriceWidgetDependencyProvider::MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT
        );
    }
}
