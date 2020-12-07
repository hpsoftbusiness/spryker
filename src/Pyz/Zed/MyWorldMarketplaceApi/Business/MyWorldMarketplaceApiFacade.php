<?php

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiBusinessFactory getFactory()
 */
class MyWorldMarketplaceApiFacade extends AbstractFacade implements MyWorldMarketplaceApiFacadeInterface
{

    public function test()
    {
        return $this->getFactory()->getMyWorldMarketplaceApiClient()->getAccessToken();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createTurnover(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()->createCreateTurnoverRequest()->request($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function cancelTurnover(OrderTransfer $orderTransfer): void
    {
        $this->getFactory()->createCancelTurnoverRequest()->request($orderTransfer);
    }
}
