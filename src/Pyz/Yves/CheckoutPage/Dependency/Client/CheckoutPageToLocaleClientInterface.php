<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Dependency\Client;

use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface as SprykerCheckoutPageToLocaleClientInterface;

interface CheckoutPageToLocaleClientInterface extends SprykerCheckoutPageToLocaleClientInterface
{
    /**
     * Specification:
     * - Format due to country.
     *
     * @api
     *
     * @param float $amount
     *
     * @return string
     */
    public function formatNumberDueToCountry(float $amount): string;
}
