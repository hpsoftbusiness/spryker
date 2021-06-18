<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Communication\Plugin\Event\Listener;

use Pyz\Zed\CustomerGroup\Dependency\CustomerGroupEvents;
use Pyz\Zed\CustomerGroupProductList\Dependency\CustomerGroupProductListEvents;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Business\CustomerGroupStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageConfig getConfig()
 */
class CustomerGroupStorageListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName)
    {
        switch ($eventName) {
            case CustomerGroupEvents::ENTITY_SPY_CUSTOMER_GROUP_CREATE:
            case CustomerGroupEvents::ENTITY_SPY_CUSTOMER_GROUP_UPDATE:
            case CustomerGroupEvents::CUSTOMER_GROUP_PUBLISH:
            case CustomerGroupProductListEvents::ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_CREATE:
            case CustomerGroupProductListEvents::ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_UPDATE:
                /* @phpstan-ignore-next-line */
                $this->getFacade()->publish((int)$transfer->getId());
                break;
            case CustomerGroupEvents::ENTITY_SPY_CUSTOMER_GROUP_DELETE:
            case CustomerGroupProductListEvents::ENTITY_PYZ_CUSTOMER_GROUP_TO_PRODUCT_LIST_DELETE:
                /* @phpstan-ignore-next-line */
                $this->getFacade()->unpublish((int)$transfer->getId());
                break;
        }
    }
}
