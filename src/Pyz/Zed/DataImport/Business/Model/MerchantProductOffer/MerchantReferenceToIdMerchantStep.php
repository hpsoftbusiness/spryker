<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer;

use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantReferenceToIdMerchantStep as SprykerMerchantReferenceToIdMerchantStep;

class MerchantReferenceToIdMerchantStep extends SprykerMerchantReferenceToIdMerchantStep
{
    public const MERCHANT_REFERENCE = 'product.value_10';
}
