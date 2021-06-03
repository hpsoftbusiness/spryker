<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAbstractOffers\Storage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Pyz\Client\ProductAbstractOffers\ProductAbstractOffersConfig;
use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Kernel\Store;

class ProductAbstractOffersReader implements ProductAbstractOffersReaderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private $productStorageClient;

    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface
     */
    private $merchantProductOfferStorageClient;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $store;

    /**
     * @var \Pyz\Client\ProductAbstractOffers\ProductAbstractOffersConfig
     */
    private $config;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Pyz\Client\ProductAbstractOffers\ProductAbstractOffersConfig $config
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        MerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient,
        Store $store,
        ProductAbstractOffersConfig $config
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
        $this->store = $store;
        $this->config = $config;
    }

    /**
     * @param int $abstractProductId
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOffersByAbstractId(
        int $abstractProductId,
        string $locale
    ): ProductOfferStorageCollectionTransfer {
        $abstractProducts = $this->productStorageClient->getProductAbstractViewTransfers(
            [$abstractProductId],
            $locale
        );
        $concretes = [];

        foreach ($abstractProducts as $abstractProduct) {
            $concretes = array_merge(
                $concretes,
                array_keys($abstractProduct->getAttributeMap()->getProductConcreteIds())
            );
        }

        $productOfferCriteriaFilterTransfer = new ProductOfferStorageCriteriaTransfer();
        $productOfferCriteriaFilterTransfer->setProductConcreteSkus(array_values($concretes));

        if ($this->config->isMultiCountryFeatureEnabled()) {
            $productOfferCriteriaFilterTransfer->setSellableIso2Code($this->getSellableCountryCode());
        }

        return $this->merchantProductOfferStorageClient->getProductOffersBySkus(
            $productOfferCriteriaFilterTransfer
        );
    }

    /**
     * @return string
     */
    private function getSellableCountryCode(): string
    {
        return $this->store->getCurrentCountry();
    }
}
