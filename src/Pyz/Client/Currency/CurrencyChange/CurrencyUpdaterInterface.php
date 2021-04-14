<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Currency\CurrencyChange;

use Spryker\Client\Currency\CurrencyChange\CurrencyUpdaterInterface as SprykerCurrencyUpdaterInterface;

interface CurrencyUpdaterInterface extends SprykerCurrencyUpdaterInterface
{
    /**
     * @param string $locale
     *
     * @return void
     */
    public function setCurrentCurrencyByLocale(string $locale): void;
}
