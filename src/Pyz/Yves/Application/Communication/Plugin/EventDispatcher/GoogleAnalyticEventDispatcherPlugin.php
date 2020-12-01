<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Application\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Pyz\Yves\Application\ApplicationConfig getConfig()
 */
class GoogleAnalyticEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const WEB_PROPERTY_ID = '_web_property_id';
    /**
     * {@inheritDoc}
     * - Extends EventDispatch with a KernelEvents::REQUEST event to add web property ID attribute from config.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event) use ($container) {
                $request = $event->getRequest();
                $request->attributes->set(static::WEB_PROPERTY_ID, $this->getConfig()->getWebPropertyId());
            }
        );

        return $eventDispatcher;
    }
}
