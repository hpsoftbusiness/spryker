<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Communication\Plugin\Event\Subscriber;

use Pyz\Zed\ProductAttribute\Dependency\ProductAttributeEvents;
use Pyz\Zed\ProductAttributeStorage\Communication\Plugin\Event\Listener\ProductManagementAttributeVisibilityBulkListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Business\ProductAttributeStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductAttributeStorage\Communication\ProductAttributeStorageCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageConfig getConfig()
 */
class ProductManagementAttributeVisibilityStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
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
            ProductAttributeEvents::ENTITY_SPY_PRODUCT_MANAGEMENT_ATTRIBUTE_UPDATE,
            new ProductManagementAttributeVisibilityBulkListener(),
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
            ProductAttributeEvents::ENTITY_SPY_PRODUCT_MANAGEMENT_ATTRIBUTE_CREATE,
            new ProductManagementAttributeVisibilityBulkListener(),
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
            ProductAttributeEvents::ENTITY_SPY_PRODUCT_MANAGEMENT_ATTRIBUTE_DELETE,
            new ProductManagementAttributeVisibilityBulkListener(),
            0,
            null,
            $this->getConfig()->getEventQueueName()
        );
    }
}
