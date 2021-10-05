<?php

declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale\Country;

/**
 * Interface CountryFormatterInterface
 *
 * @package Pyz\Client\Locale\Country
 */
interface DecimalCountryFormatterInterface
{
    /**
     * @param float $amount
     *
     * @return string
     */
    public function format(float $amount): string;
}
