<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Communication\Plugin\Event\Subscriber;

use Pyz\Zed\ProductAbstractAttribute\Communication\Plugin\Event\Listener\ProductAbstractPublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Business\ProductAbstractAttributeFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductAbstractAttribute\Communication\ProductAbstractAttributeCommunicationFactory getFactory()
 */
class ProductAbstractAttributeEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addProductAbstractPublishListener($eventCollection);
        $this->addProductAbstractCreateListener($eventCollection);
        $this->addProductAbstractUpdateListener($eventCollection);
        $this->addProductAbstractDeleteListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
            new ProductAbstractPublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_CREATE,
            new ProductAbstractPublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE,
            new ProductAbstractPublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductAbstractDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_DELETE,
            new ProductAbstractPublishListener()
        );
    }
}
