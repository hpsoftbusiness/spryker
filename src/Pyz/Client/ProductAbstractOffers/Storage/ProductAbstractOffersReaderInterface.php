<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAbstractOffers\Storage;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

interface ProductAbstractOffersReaderInterface
{
    /**
     * @param int $abstractProductId
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOffersByAbstractId(
        int $abstractProductId,
        string $locale
    ): ProductOfferStorageCollectionTransfer;
}
