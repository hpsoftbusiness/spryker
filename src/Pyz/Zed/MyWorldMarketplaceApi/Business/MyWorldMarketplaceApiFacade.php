<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TurnoverTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiBusinessFactory getFactory()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface getEntityManager()
 */
class MyWorldMarketplaceApiFacade extends AbstractFacade implements MyWorldMarketplaceApiFacadeInterface
{
    /**
     * @param int[] $orderItemIds
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createTurnover(array $orderItemIds, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()->createCreateTurnoverRequest()->request($orderItemIds, $orderTransfer);
    }

    /**
     * @param int[] $orderItemIds
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\TurnoverTransfer $turnoverTransfer
     *
     * @return void
     */
    public function cancelTurnover(array $orderItemIds, OrderTransfer $orderTransfer, TurnoverTransfer $turnoverTransfer): void
    {
        $this->getFactory()->createCancelTurnoverRequest()->request($orderItemIds, $orderTransfer, $turnoverTransfer);
    }
}
