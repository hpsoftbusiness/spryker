<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale;

use Negotiation\LanguageNegotiator;
use Pyz\Client\Customer\CustomerClientInterface;
use Pyz\Yves\Locale\Language\LanguageNegotiationHandler;
use Pyz\Yves\Locale\Language\LanguageNegotiationHandlerInterface;
use Spryker\Yves\Locale\LocaleFactory as SprykerLocaleFactory;

class LocaleFactory extends SprykerLocaleFactory
{
    /**
     * @return \Pyz\Yves\Locale\Language\LanguageNegotiationHandlerInterface
     */
    public function createLanguageNegotiationHandler(): LanguageNegotiationHandlerInterface
    {
        return new LanguageNegotiationHandler(
            $this->getStore(),
            $this->createNegotiator()
        );
    }

    /**
     * @return \Negotiation\LanguageNegotiator
     */
    public function createNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }

    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::CLIENT_CUSTOMER);
    }
}
