<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersWidget\DataProvider;

use Generated\Shared\Transfer\ProductAffiliateOffersWidgetTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;

class AffiliateDataProvider implements AffiliateDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAffiliateOffersWidgetTransfer[]
     */
    public function getData(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): array
    {
        $offers = [];
        if (count($productOfferStorageCollectionTransfer->getProductOffersStorage()) === 0) {
            return $offers;
        }
        foreach ($productOfferStorageCollectionTransfer->getProductOffersStorage() as $productOffer) {
            $productAffiliateOffersWidgetTransfer = new ProductAffiliateOffersWidgetTransfer();
            $merchantProfile = $productOffer->getMerchantStorage()->getMerchantProfile();

            if ($merchantProfile !== null) {
                $productAffiliateOffersWidgetTransfer->setLogo(
                    $merchantProfile->getLogoUrl() ?? null
                );
                $productAffiliateOffersWidgetTransfer->setDetailPageUrl(
                    $merchantProfile->getDetailPageUrl() ?? null
                );
                $productAffiliateOffersWidgetTransfer->setStandardCashback(
                    $merchantProfile->getStandardCashback() ?? null
                );
                $productAffiliateOffersWidgetTransfer->setStandardStoryPoints(
                    $merchantProfile->getStandardStoryPoints() ?? null
                );
            }
            $offers[] = $productAffiliateOffersWidgetTransfer;
        }

        return $offers;
    }
}
