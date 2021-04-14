<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale\Country;

use Spryker\Shared\Kernel\Store;

class LocaleCountryResolver implements LocaleCountryResolverInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $store;

    /**
     * @var string[]
     */
    private $countryToLocaleMap;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param string[] $countryToLocaleMap
     */
    public function __construct(Store $store, array $countryToLocaleMap)
    {
        $this->store = $store;
        $this->countryToLocaleMap = $countryToLocaleMap;
    }

    /**
     * @return string
     */
    public function resolveCountryCodeForCurrentLocale(): string
    {
        $currentLocale = $this->getCurrentLocale();
        $countryCode = $this->findMappedCountryCode($currentLocale);
        if ($countryCode) {
            return $countryCode;
        }

        return $this->extractCountryCodeFromLocale($currentLocale);
    }

    /**
     * @param string $countryIsoCode
     *
     * @return string|null
     */
    public function findLocaleRelatedByCountryCode(string $countryIsoCode): ?string
    {
        if (!$this->isCountrySupported($countryIsoCode)) {
            return null;
        }

        return $this->findMappedLocaleByCountryCode($countryIsoCode);
    }

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function findMappedCountryCode(string $locale): ?string
    {
        foreach ($this->countryToLocaleMap as $countryCode => $localeCode) {
            if ($locale === $localeCode) {
                return $countryCode;
            }
        }

        return null;
    }

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    private function isCountrySupported(string $countryCode): bool
    {
        $storeCountries = $this->store->getCountries();

        return in_array(strtoupper($countryCode), $storeCountries);
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    private function extractCountryCodeFromLocale(string $locale): string
    {
        return explode('_', $locale)[1];
    }

    /**
     * @param string $countryCode
     *
     * @return string|null
     */
    private function findMappedLocaleByCountryCode(string $countryCode): ?string
    {
        return $this->countryToLocaleMap[strtoupper($countryCode)] ?? null;
    }

    /**
     * @return string
     */
    private function getCurrentLocale(): string
    {
        return $this->store->getCurrentLocale();
    }
}
