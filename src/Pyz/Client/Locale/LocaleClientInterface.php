<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale;

use Spryker\Client\Locale\LocaleClientInterface as SprykerLocaleClientInterface;

interface LocaleClientInterface extends SprykerLocaleClientInterface
{
    /**
     * Specification:
     * - Returns country code mapped for current locale in config.
     * - If locale is not mapped for any country code, extracts country from locale code.
     *
     * @return string
     */
    public function getCountryCodeForCurrentLocale(): string;

    /**
     * Specification:
     * - Returns country code mapped for current locale in config.
     *
     * @param string $locale
     *
     * @return string|null
     */
    public function findMappedCountryCodeByLocale(string $locale): ?string;

    /**
     * Specification:
     * - Returns locale name mapped for provided country code in config.
     * - If mapping for provided country code is missing, returns null.
     *
     * @param string $countryIsoCode
     *
     * @return string|null
     */
    public function findLocaleRelatedByCountryCode(string $countryIsoCode): ?string;
}