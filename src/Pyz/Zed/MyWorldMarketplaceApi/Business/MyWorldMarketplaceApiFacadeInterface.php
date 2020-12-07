<?php

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Generated\Shared\Transfer\OrderTransfer;

interface MyWorldMarketplaceApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createTurnover(OrderTransfer $orderTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function cancelTurnover(OrderTransfer $orderTransfer): void;
}
