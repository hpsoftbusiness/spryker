<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Security;

use Pyz\Yves\CustomerPage\Security\Guard\SsoAuthenticator;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use SprykerShop\Shared\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Plugin\Security\CustomerPageSecurityPlugin as SprykerCustomerPageSecurityPlugin;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerPageSecurityPlugin extends SprykerCustomerPageSecurityPlugin
{
    protected const SERVICE_SECURITY_HTTP_UTILS = 'security.http_utils';

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = parent::extend($securityBuilder, $container);

        if ($this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            $this->addAuthenticator($container);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        if (!$this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            return parent::addFirewalls($securityBuilder);
        }

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

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticator(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.guard.authenticator.sso', function (ContainerInterface $container) {
            return new SsoAuthenticator(
                $container->get(static::SERVICE_SECURITY_HTTP_UTILS),
                $this->getFactory()->getSsoClient(),
                $this->getFactory()->createCustomerAuthenticationSuccessHandler(),
                $this->getFactory()->createCustomerAuthenticationFailureHandler(),
                $this->getLocale(),
                $this->getFactory()->getCustomerClient()
            );
        });

        return $container;
    }
}
