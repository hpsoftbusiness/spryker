<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale\Country;

use NumberFormatter;

/**
 * Class CountryFormatter
 *
 * @package Pyz\Client\Locale\Country
 */
class DecimalCountryFormatter implements DecimalCountryFormatterInterface
{
    /**
     * @var \NumberFormatter
     */
    private $numberFormatter;

    /**
     * @param \NumberFormatter $numberFormatter
     */
    public function __construct(NumberFormatter $numberFormatter)
    {
        $this->numberFormatter = $numberFormatter;
    }

    /**
     * @param float $amount
     *
     * @return string
     */
    public function format(float $amount): string
    {
        $formatter = $this->numberFormatter;

        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $formatter->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 2);

        return $formatter->format($amount);
    }
}
