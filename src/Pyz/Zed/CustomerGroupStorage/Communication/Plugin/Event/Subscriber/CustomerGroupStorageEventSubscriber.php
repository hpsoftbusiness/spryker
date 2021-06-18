<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Communication\Plugin\Event\Subscriber;

use Pyz\Zed\CustomerGroup\Dependency\CustomerGroupEvents;
use Pyz\Zed\CustomerGroupProductList\Dependency\CustomerGroupProductListEvents;
use Pyz\Zed\CustomerGroupStorage\Communication\Plugin\Event\Listener\CustomerGroupStorageListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroupStorage\Business\CustomerGroupStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageConfig getConfig()
 */
class CustomerGroupStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(
            CustomerGroupEvents::ENTITY_SPY_CUSTOMER_GROUP_CREATE,
            new CustomerGroupStorageListener()
        );
        $eventCollection->addListenerQueued(
            CustomerGroupEvents::ENTITY_SPY_CUSTOMER_GROUP_UPDATE,
            new CustomerGroupStorageListener()
        );
        $eventCollection->addListenerQueued(
            CustomerGroupEvents::ENTITY_SPY_CUSTOMER_GROUP_DELETE,
            new CustomerGroupStorageListener()
        );
        $eventCollection->addListenerQueued(
            CustomerGroupEvents::CUSTOMER_GROUP_PUBLISH,
            new CustomerGroupStorageListener()
        );
        $eventCollection->addListenerQueued(
            CustomerGroupProductListEvents::ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_CREATE,
            new CustomerGroupStorageListener()
        );
        $eventCollection->addListenerQueued(
            CustomerGroupProductListEvents::ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_UPDATE,
            new CustomerGroupStorageListener()
        );
        $eventCollection->addListenerQueued(
            CustomerGroupProductListEvents::ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_DELETE,
            new CustomerGroupStorageListener()
        );

        return $eventCollection;
    }
}
