<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale\Country;

interface LocaleCountryResolverInterface
{
    /**
     * @return string
     */
    public function resolveCountryCodeForCurrentLocale(): string;

    /**
     * @param string $locale
     *
     * @return string|null
     */
    public function findMappedCountryCode(string $locale): ?string;

    /**
     * @param string $countryIsoCode
     *
     * @return string|null
     */
    public function findLocaleRelatedByCountryCode(string $countryIsoCode): ?string;
}
