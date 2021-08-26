<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiBusinessFactory getFactory()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface getEntityManager()
 */
class MyWorldMarketplaceApiFacade extends AbstractFacade implements MyWorldMarketplaceApiFacadeInterface
{
    /**
     * @param int $orderItemId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createTurnover(int $orderItemId, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()->createCreateTurnoverRequest($orderItemId, $orderTransfer)->send();
    }

    /**
     * @param int $orderItemId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function cancelTurnover(int $orderItemId, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()->createCancelTurnoverRequest($orderItemId, $orderTransfer)->send();
    }
}
