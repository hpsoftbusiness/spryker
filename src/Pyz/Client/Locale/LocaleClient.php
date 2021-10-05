<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale;

use Spryker\Client\Locale\LocaleClient as SprykerLocaleClient;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientInterface;

/**
 * @method \Pyz\Client\Locale\LocaleFactory getFactory()
 */
class LocaleClient extends SprykerLocaleClient implements
    LocaleClientInterface,
    SearchElasticsearchToLocaleClientInterface
{
    /**
     * @return string
     */
    public function getCountryCodeForCurrentLocale(): string
    {
        return $this->getFactory()->createLocaleCountryResolver()->resolveCountryCodeForCurrentLocale();
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function findMappedCountryCodeByLocale(string $locale): ?string
    {
        return $this->getFactory()->createLocaleCountryResolver()->findMappedCountryCode($locale);
    }

    /**
     * @param string $countryIsoCode
     *
     * @return string|null
     */
    public function findLocaleRelatedByCountryCode(string $countryIsoCode): ?string
    {
        return $this->getFactory()->createLocaleCountryResolver()->findLocaleRelatedByCountryCode($countryIsoCode);
    }

    /**
     * @param float $amount
     *
     * @return string
     */
    public function formatNumberDueToCountry(float $amount): string
    {
        return $this->getFactory()->createDecimalCountryFormatter()->format($amount);
    }
}
