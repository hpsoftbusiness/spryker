<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore;

use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceTypeToIdPriceTypeStep as SprykerPriceTypeToIdPriceTypeStep;

class PriceTypeToIdPriceTypeStep extends SprykerPriceTypeToIdPriceTypeStep
{
    protected const PRICE_TYPE = 'product_price.price_type';
}
