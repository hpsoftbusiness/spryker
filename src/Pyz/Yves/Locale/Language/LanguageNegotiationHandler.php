<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale\Language;

use Negotiation\AcceptLanguage;
use Negotiation\LanguageNegotiator as CoreLanguageNegotiator;
use Spryker\Shared\Kernel\Store;

class LanguageNegotiationHandler implements LanguageNegotiationHandlerInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $store;

    /**
     * @var \Negotiation\LanguageNegotiator
     */
    private $negotiator;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Negotiation\LanguageNegotiator $negotiator
     */
    public function __construct(
        Store $store,
        CoreLanguageNegotiator $negotiator
    ) {
        $this->store = $store;
        $this->negotiator = $negotiator;
    }

    /**
     * @param string $acceptLanguage
     *
     * @return string|null
     */
    public function getLanguageIsoCode(string $acceptLanguage): ?string
    {
        $storeLanguages = array_keys($this->store->getLocales());

        if (!$acceptLanguage) {
            return null;
        }

        $language = $this->findAcceptedLanguage($acceptLanguage, $storeLanguages);

        return $language ? $language->getType() : null;
    }

    /**
     * @param string $acceptLanguage
     * @param array $storeCountries
     *
     * @return \Negotiation\AcceptLanguage|null
     */
    private function findAcceptedLanguage(string $acceptLanguage, array $storeCountries): ?AcceptLanguage
    {
        /** @var \Negotiation\AcceptLanguage $acceptedLanguage */
        $acceptedLanguage = $this->negotiator->getBest($acceptLanguage, $storeCountries);

        return $acceptedLanguage;
    }
}
