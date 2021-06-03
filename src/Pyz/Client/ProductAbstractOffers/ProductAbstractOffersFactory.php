<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAbstractOffers;

use Pyz\Client\ProductAbstractOffers\Storage\ProductAbstractOffersReader;
use Pyz\Client\ProductAbstractOffers\Storage\ProductAbstractOffersReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClient;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Pyz\Client\ProductAbstractOffers\ProductAbstractOffersConfig getConfig()
 */
class ProductAbstractOffersFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\ProductAbstractOffers\Storage\ProductAbstractOffersReaderInterface
     */
    public function createProductAbstractOffersReader(): ProductAbstractOffersReaderInterface
    {
        return new ProductAbstractOffersReader(
            $this->getProductStorageClient(),
            $this->getMerchantProductOfferStorageClient(),
            $this->getStore(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAbstractOffersDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClient
     */
    public function getMerchantProductOfferStorageClient(): MerchantProductOfferStorageClient
    {
        return $this->getProvidedDependency(
            ProductAbstractOffersDependencyProvider::MERCHANT_PRODUCT_OFFER_STORAGE_CLIENT
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(ProductAbstractOffersDependencyProvider::STORE);
    }
}
