<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Currency\CurrencyChange;

use NumberFormatter;
use Spryker\Client\Currency\CurrencyChange\CurrencyUpdater as SprykerCurrencyUpdater;
use Spryker\Client\Currency\Exception\CurrencyNotExistsException;

class CurrencyUpdater extends SprykerCurrencyUpdater implements CurrencyUpdaterInterface
{
    /**
     * @param string $locale
     *
     * @return void
     */
    public function setCurrentCurrencyByLocale(string $locale): void
    {
        $currencyIsoCode = $this->getLocaleCurrencyIsoCode($locale);

        try {
            $this->setCurrentCurrencyIsoCode($currencyIsoCode);
        } catch (CurrencyNotExistsException $exception) {
        }
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    private function getLocaleCurrencyIsoCode(string $locale): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);
    }
}
