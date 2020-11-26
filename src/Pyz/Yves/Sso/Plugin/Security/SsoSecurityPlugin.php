<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Sso\Plugin\Security;

use Pyz\Yves\Sso\Security\Guard\SsoAuthenticator;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Pyz\Client\Sso\SsoClientInterface getClient()
 * @method \Pyz\Yves\Sso\SsoConfig getConfig()
 */
class SsoSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const SERVICE_SECURITY_HTTP_UTILS = 'security.http_utils';

    /**
     * @var array
     */
    protected $securityRoutes;

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $this->addAuthenticator($container);

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
                $this->getClient(),
                $this->getConfig(),
                $this->getLocale()
            );
        });

        return $container;
    }
}
