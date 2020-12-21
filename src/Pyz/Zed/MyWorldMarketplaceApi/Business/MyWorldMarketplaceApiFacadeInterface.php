<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;

interface MyWorldMarketplaceApiFacadeInterface
{
    /**
     * @param int[] $orderItemIds
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function createTurnover(array $orderItemIds, OrderTransfer $orderTransfer): void;

    /**
     * @param int[] $orderItemIds
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    public function cancelTurnover(array $orderItemIds, OrderTransfer $orderTransfer, RefundTransfer $refundTransfer): void;
}
