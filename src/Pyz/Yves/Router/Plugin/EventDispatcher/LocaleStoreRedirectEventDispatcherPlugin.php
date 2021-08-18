<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Router\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ErrorPage\Plugin\Router\ErrorPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Use this plugin when you need to redirect to 404 if locale is not exist in store.
 *
 * @method \Pyz\Yves\Router\RouterConfig getConfig()
 */
class LocaleStoreRedirectEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a Listener to the EventDispatcher.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(
        EventDispatcherInterface $eventDispatcher,
        ContainerInterface $container
    ): EventDispatcherInterface {
        $eventDispatcher = $this->addListener($eventDispatcher);

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    private function addListener(EventDispatcherInterface $eventDispatcher): EventDispatcherInterface
    {
        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event): void {
                $request = $event->getRequest();
                $localsByStore = $this->getConfig()->getLocalsByStore($this->getConfig()->getStorePrefix());
                $local = $this->getLocale();
                if (!in_array($local, $localsByStore) &&
                    !str_contains(
                        $request->getUri(),
                        ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_404
                    )) {
                    $event->setResponse(new RedirectResponse('/' . ErrorPageRouteProviderPlugin::ROUTE_NAME_ERROR_404));
                }
            }
        );

        return $eventDispatcher;
    }
}
