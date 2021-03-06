<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MerchantProductOfferStorage;

use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageDependencyProvider as SprykerMerchantProductOfferStorageDependencyProvider;
use Spryker\Client\MerchantProductOfferStorage\Plugin\MerchantProductOfferStorage\DefaultProductOfferReferenceStrategyPlugin;
use Spryker\Client\MerchantProductOfferStorage\Plugin\MerchantProductOfferStorage\ProductOfferReferenceStrategyPlugin;
use Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;
use Spryker\Client\PriceProductOfferStorage\Plugin\PriceProductStorage\LowestPriceProductOfferStorageCollectionSorterPlugin;
use Spryker\Client\PriceProductOfferStorage\Plugin\PriceProductStorage\PriceProductOfferStorageExpanderPlugin;

class MerchantProductOfferStorageDependencyProvider extends SprykerMerchantProductOfferStorageDependencyProvider
{
    /**
     * @return array
     */
    protected function getProductOfferReferenceStrategyPlugins(): array
    {
        return [
            new ProductOfferReferenceStrategyPlugin(),
            new DefaultProductOfferReferenceStrategyPlugin(),
            new PriceProductOfferStorageExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface
     */
    protected function createProductOfferStorageCollectionSorterPlugin(): ProductOfferStorageCollectionSorterPluginInterface
    {
        return new LowestPriceProductOfferStorageCollectionSorterPlugin();
    }

    /**
     * @return \Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface[]
     */
    protected function getProductOfferStorageExpanderPlugins(): array
    {
        return [
            new PriceProductOfferStorageExpanderPlugin(),
        ];
    }
}
