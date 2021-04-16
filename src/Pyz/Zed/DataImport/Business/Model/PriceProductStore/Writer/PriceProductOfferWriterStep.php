<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer;

use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductOfferWriterStep as SprykerPriceProductOfferWriterStep;

class PriceProductOfferWriterStep extends SprykerPriceProductOfferWriterStep
{
    public const BULK_SIZE = 1000;
}
