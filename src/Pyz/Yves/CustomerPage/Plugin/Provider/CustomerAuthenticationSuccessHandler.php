<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Provider;

use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerAuthenticationSuccessHandler as SprykerCustomerAuthenticationSuccessHandler;

class CustomerAuthenticationSuccessHandler extends SprykerCustomerAuthenticationSuccessHandler
{
    /**
     * @see HomePageRouteProviderPlugin::ROUTE_HOME
     */
    protected const ROUTE_HOME = '/';
}
