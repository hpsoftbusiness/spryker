<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore;

use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PreparePriceDataStep as SprykerPreparePriceDataStep;

class PreparePriceDataStep extends SprykerPreparePriceDataStep
{
    protected const PRICE_DATA_VOLUME_PRICES = 'product_price.price_data.volume_prices';
}
