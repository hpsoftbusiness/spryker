<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Sso\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Pyz\Yves\Sso\SsoFactory getFactory()
 * @method \Pyz\Client\Sso\SsoClientInterface getClient()
 */
class AutoLoginPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
            $eventDispatcher->addListener(
                KernelEvents::REQUEST,
                function (RequestEvent $event) {

                    $autoLogin = (strpos($_SERVER['REQUEST_URI'], 'auto_login=true') !== false);

                    if ($autoLogin && !$this->getFactory()->getCustomerClient()->isLoggedIn()) {
                        $event->setResponse(new RedirectResponse($this->getClient()->getAuthorizeUrl(
                            $this->getLocale(),
                            Config::get(ApplicationConstants::BASE_URL_YVES) . $event->getRequest()->getPathInfo()
                        )));
                    }
                }
            );

        return $eventDispatcher;
    }
}
