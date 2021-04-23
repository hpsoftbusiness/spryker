<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MerchantProductOfferStorage;

use Pyz\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReader;
use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageFactory as SprykerMerchantProductOfferStorageFactory;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface;

class MerchantProductOfferStorageFactory extends SprykerMerchantProductOfferStorageFactory
{
    /**
     * @return \Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getStorageClient(),
            $this->createMerchantProductOfferMapper(),
            $this->getMerchantStorageClient(),
            $this->getUtilEncodingService(),
            $this->createProductOfferStorageKeyGenerator(),
            $this->getProductOfferStorageCollectionSorterPlugin(),
            $this->getProductOfferStorageExpanderPlugins()
        );
    }
}
