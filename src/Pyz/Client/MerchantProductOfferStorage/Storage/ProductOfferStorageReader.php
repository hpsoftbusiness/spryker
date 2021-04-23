<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MerchantProductOfferStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Client\MerchantProductOfferStorage\Storage\ProductOfferStorageReader as SprykerProductOfferStorageReader;

class ProductOfferStorageReader extends SprykerProductOfferStorageReader
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOffersBySkus(
        ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
    ): ProductOfferStorageCollectionTransfer {
        $productOfferStorageCollectionTransfer = new ProductOfferStorageCollectionTransfer();

        $productConcreteSkus = $productOfferStorageCriteriaTransfer->getProductConcreteSkus();
        if (!$productConcreteSkus) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOfferReferences = $this->getProductOfferReferences($productConcreteSkus);
        if (!$productOfferReferences) {
            return $productOfferStorageCollectionTransfer;
        }

        $productOfferStorageTransfers = $this->getProductOfferStorageByReferences(
            array_unique(array_filter($productOfferReferences))
        );
        if (!$productOfferStorageTransfers) {
            return $productOfferStorageCollectionTransfer;
        }

        if ($productOfferStorageCriteriaTransfer->getMerchantReference()) {
            $productOfferStorageTransfers = $this->filterProductOfferStorageTransfersByMerchantReference(
                $productOfferStorageTransfers,
                $productOfferStorageCriteriaTransfer->getMerchantReference()
            );
        }
        if ($productOfferStorageCriteriaTransfer->getSellableIso2Code()) {
            $productOfferStorageTransfers = $this->filterProductOfferStorageTransfersBySellableIso2Code(
                $productOfferStorageTransfers,
                $productOfferStorageCriteriaTransfer->getSellableIso2Code()
            );
        }

        $productOfferStorageTransfers = $this->expandProductOffersWithMerchants($productOfferStorageTransfers);
        $productOfferStorageTransfers = $this->executeProductOfferStorageExpanderPlugins($productOfferStorageTransfers);

        $productOfferStorageCollectionTransfer->setProductOffersStorage(new ArrayObject($productOfferStorageTransfers));
        $productOfferStorageCollectionTransfer = $this->productOfferStorageCollectionSorterPlugin
            ->sort($productOfferStorageCollectionTransfer);

        return $this->expandProductOffersWithDefaultProductOffer($productOfferStorageCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer[] $productOfferStorageTransfers
     * @param string $sellableIso2Code
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    private function filterProductOfferStorageTransfersBySellableIso2Code(
        array $productOfferStorageTransfers,
        string $sellableIso2Code
    ): array {
        $filteredProductOfferStorageTransfers = [];

        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $merchant = $this->merchantStorageClient->findOne($productOfferStorageTransfer->getIdMerchant());
            $addressCollection = $merchant->getMerchantProfile()->getAddressCollection();

            foreach ($addressCollection as $addressTransfer) {
                if ($addressTransfer->getIso2Code() && strtolower($addressTransfer->getIso2Code()) !== strtolower($sellableIso2Code)) {
                    continue 2;
                }
            }

            $filteredProductOfferStorageTransfers[] = $productOfferStorageTransfer;
        }

        return $filteredProductOfferStorageTransfers;
    }
}
