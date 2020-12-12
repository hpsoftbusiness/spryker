<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
