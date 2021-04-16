<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer;

use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferStoreWriterStep as SprykerMerchantProductOfferStoreWriterStep;

class MerchantProductOfferStoreWriterStep extends SprykerMerchantProductOfferStoreWriterStep
{
    public const BULK_SIZE = 1000;
}
