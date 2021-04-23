<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\ProductViewTransfer;
use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGenerator;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader as SprykerMerchantProductOfferReader;

class MerchantProductOfferReader extends SprykerMerchantProductOfferReader
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOffers(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        $productOffersStorageTransfers = parent::getProductOffers($productViewTransfer, $localeName);
        foreach ($productOffersStorageTransfers as $productOffersStorageTransfer) {
            $affiliateData = $productOffersStorageTransfer->getAffiliateData();
            if (!is_array($affiliateData)) {
                $affiliateData = json_decode((string)$affiliateData, true);
            }

            $affiliateData[ProductAffiliateLinkGenerator::KEY_OFFICE_DEALER_ID] =
                $productOffersStorageTransfer->getMerchantReference();
            $productOffersStorageTransfer->setAffiliateData($affiliateData);
        }

        return $productOffersStorageTransfers;
    }
}
