<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersWidget\DataProvider;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

interface AffiliateDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return array
     */
    public function getData(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): array;
}
