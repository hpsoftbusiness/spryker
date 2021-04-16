<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer;

use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductStoreWriterStep as SprykerPriceProductStoreWriterStep;

class PriceProductStoreWriterStep extends SprykerPriceProductStoreWriterStep
{
    protected const VALUE_NET = 'product.value_75';
    protected const VALUE_GROSS = 'product.value_75';
    public const BULK_SIZE = 1000;
}
