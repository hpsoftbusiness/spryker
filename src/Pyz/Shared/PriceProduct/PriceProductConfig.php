<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\PriceProduct;

use Spryker\Shared\PriceProduct\PriceProductConfig as SprykerPriceProductConfig;

class PriceProductConfig extends SprykerPriceProductConfig
{
    public const PRICE_TYPE_BENEFIT = 'BENEFIT';
    public const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @return string
     */
    public function getPriceTypeBenefitName(): string
    {
        return self::PRICE_TYPE_BENEFIT;
    }

    /**
     * @return string
     */
    public function getPriceTypeOriginalName(): string
    {
        return self::PRICE_TYPE_ORIGINAL;
    }
}
