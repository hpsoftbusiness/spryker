<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Plugin\Event\Subscriber;

use Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener\WeclappProductCategoryExportBulkListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;

/**
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 * @method \Pyz\Zed\Weclapp\Communication\WeclappCommunicationFactory getFactory()
 */
class WeclappProductCategoryEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
        $this->addDeleteListener($eventCollection);

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
            ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_UPDATE,
            new WeclappProductCategoryExportBulkListener(),
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
            ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE,
            new WeclappProductCategoryExportBulkListener(),
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
    protected function addDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_DELETE,
            new WeclappProductCategoryExportBulkListener(),
            0,
            null,
            $this->getConfig()->getEventQueueName()
        );
    }
}
