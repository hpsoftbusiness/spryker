<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAbstractOffers;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductAbstractOffers\ProductAbstractOffersFactory getFactory()
 */
class ProductAbstractOffersClient extends AbstractClient implements ProductAbstractOffersClientInterface
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
    ): ProductOfferStorageCollectionTransfer {
        return $this->getFactory()->createProductAbstractOffersReader()->getProductOffersByAbstractId(
            $abstractProductId,
            $locale
        );
    }
}
