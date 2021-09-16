<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale\Plugin\Locale;

use Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin as SprykerLocaleLocalePlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 * @method \Pyz\Yves\Locale\LocaleFactory getFactory()
 * @method \Pyz\Yves\Locale\LocaleConfig getConfig()
 */
class LocaleLocalePlugin extends SprykerLocaleLocalePlugin
{
    public const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        $currentLocale = $this->getClient()->getCurrentLocale();
        $store = $this->getFactory()->getStore()->getStoreName();
        $locales = $this->getConfig()->getLocalsByStore($store);
        $requestUri = $this->getRequestUri();
        $headerLanguage = $this->extractRequestHeaderLocaleCode();
        if ($headerLanguage) {
            $this->getContainer()->set(self::HEADER_ACCEPT_LANGUAGE, $headerLanguage);
        }

        if ($requestUri) {
            $localeCode = $this->extractLocaleCode($requestUri);
            if ($localeCode !== false && isset($locales[$localeCode])) {
                return $locales[$localeCode];
            }
        }
//         TODO:: uncomment and fix
//        $customerCountryLocale = $this->getCustomerCountryLocale();
//        if ($customerCountryLocale) {
//            return $customerCountryLocale;
//        }

        return $locales[$headerLanguage] ?? $currentLocale;
    }

    /**
     * @return string|null
     */
    protected function getRequestUri(): ?string
    {
        return $this->getRequest()->server->get(static::REQUEST_URI);
    }

    /**
     * @return string|null
     */
    private function extractRequestHeaderLocaleCode(): ?string
    {
        $acceptLanguage = (string)$this->getRequest()->headers->get(self::HEADER_ACCEPT_LANGUAGE, '');

        return $this->negotiateLanguage($acceptLanguage);
    }

    /**
     * @param string $acceptLanguage
     *
     * @return string|null
     */
    private function negotiateLanguage(string $acceptLanguage): ?string
    {
        return $this->getFactory()->createLanguageNegotiationHandler()->getLanguageIsoCode($acceptLanguage);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest(): Request
    {
        if ($this->request === null) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }

//    /**
//     * @return string|null
//     */
//    private function getCustomerCountryLocale(): ?string
//    {
//        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
//        if (!$customerTransfer || !$customerTransfer->getCountryId()) {
//            return null;
//        }
//
//        $countryCode = $customerTransfer->getCountryId();
//        if (!$this->isCountrySupported($countryCode)) {
//            return null;
//        }
//
//        return $this->getLocaleByCountryCode($countryCode);
//    }
//
//    /**
//     * @param string $countryCode
//     *
//     * @return bool
//     */
//    private function isCountrySupported(string $countryCode): bool
//    {
//        $storeCountries = $this->getFactory()->getStore()->getCountries();
//
//        return in_array(strtoupper($countryCode), $storeCountries);
//    }
//
//    /**
//     * @param string $countryCode
//     *
//     * @return string|null
//     */
//    private function getLocaleByCountryCode(string $countryCode): ?string
//    {
//        $countryToLocaleRelations = $this->getConfig()->getCountryToLocaleRelations();
//
//        return $countryToLocaleRelations[strtoupper($countryCode)] ?? null;
//    }
}
