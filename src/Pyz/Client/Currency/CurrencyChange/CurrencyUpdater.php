<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Currency\CurrencyChange;

use NumberFormatter;
use Spryker\Client\Currency\CurrencyChange\CurrencyUpdater as SprykerCurrencyUpdater;
use Spryker\Client\Currency\Exception\CurrencyNotExistsException;
use Spryker\Shared\Log\LoggerTrait;

class CurrencyUpdater extends SprykerCurrencyUpdater implements CurrencyUpdaterInterface
{
    use LoggerTrait;

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
            $this->setCurrentCurrencyIsoCode($this->storeClient->getCurrentStore()->getDefaultCurrencyIsoCode());
            $this->getLogger()->info('Currency ' . $currencyIsoCode . ' was not found in the config');
        }
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    private function getLocaleCurrencyIsoCode(string $locale): string
    {
        // Otherwise guest users with EN locale get the dollar currency and don't see any products
        // TODO: how do we deal with american users?
        if ($locale === 'en_US') {
            return 'EUR';
        }

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);
    }
}
