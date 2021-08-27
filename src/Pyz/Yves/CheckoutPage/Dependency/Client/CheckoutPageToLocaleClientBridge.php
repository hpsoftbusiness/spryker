<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Dependency\Client;

use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientBridge as SprykerCheckoutPageToLocaleClientBridge;

/**
 * @property \Pyz\Client\Locale\LocaleClientInterface $localeClient
 */
class CheckoutPageToLocaleClientBridge extends SprykerCheckoutPageToLocaleClientBridge implements CheckoutPageToLocaleClientInterface
{
    /**
     * @param \Pyz\Client\Locale\LocaleClientInterface $localeClient
     */
    public function __construct($localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @param float $amount
     *
     * @return string
     */
    public function formatNumberDueToCountry(float $amount): string
    {
        return $this->localeClient->formatNumberDueToCountry($amount);
    }
}
