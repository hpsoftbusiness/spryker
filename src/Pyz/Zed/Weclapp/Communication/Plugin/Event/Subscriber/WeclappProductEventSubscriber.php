<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Plugin\Event\Subscriber;

use Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener\WeclappProductExportBulkListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 * @method \Pyz\Zed\Weclapp\Communication\WeclappCommunicationFactory getFactory()
 */
class WeclappProductEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addCreateListener($eventCollection);
        $this->addUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductEvents::ENTITY_SPY_PRODUCT_UPDATE,
            new WeclappProductExportBulkListener(),
            0,
            null,
            $this->getConfig()->getEventQueueName()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductEvents::ENTITY_SPY_PRODUCT_CREATE,
            new WeclappProductExportBulkListener(),
            0,
            null,
            $this->getConfig()->getEventQueueName()
        );
    }
}
