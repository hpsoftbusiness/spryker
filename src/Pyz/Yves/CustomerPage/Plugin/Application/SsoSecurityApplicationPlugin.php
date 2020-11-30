<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class SsoSecurityApplicationPlugin extends SecurityApplicationPlugin
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        if (!$this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            return $container;
        }

        $this->addSecurityRoute('match', '/' . $this->getFactory()->getSsoClient()->getLoginCheckPath());

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        if (!$this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            return $container;
        }

        $this->addRouter($container);

        return $container;
    }
}
