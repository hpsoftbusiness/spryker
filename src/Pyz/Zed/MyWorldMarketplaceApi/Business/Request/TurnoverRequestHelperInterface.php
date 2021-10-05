<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface TurnoverRequestHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getCustomerId(OrderTransfer $orderTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function getDealerId(OrderTransfer $orderTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function getTurnoverReference(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): string;

    /**
     * @param int $orderItemId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function extractOrderItemFromOrderTransferById(
        int $orderItemId,
        OrderTransfer $orderTransfer
    ): ItemTransfer;
}
