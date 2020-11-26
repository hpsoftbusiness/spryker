<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Security;

use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerPageSecurityPlugin as SprykerCustomerPageSecurityPlugin;

class CustomerPageSecurityPlugin extends SprykerCustomerPageSecurityPlugin
{
    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(CustomerPageConfig::SECURITY_FIREWALL_NAME, [
            'anonymous' => true,
            'pattern' => '^/',
            'guard' => [
                'authenticators' => [
                    'security.guard.authenticator.sso',
                ],
                'entry_point' => 'security.guard.authenticator.sso',
            ],
            'logout' => [
                'logout_path' => static::ROUTE_LOGOUT,
                'target_url' => static::ROUTE_HOME,
            ],
            'users' => function () {
                return $this->getFactory()->createCustomerUserProvider();
            },
        ]);

        return $securityBuilder;
    }
}
