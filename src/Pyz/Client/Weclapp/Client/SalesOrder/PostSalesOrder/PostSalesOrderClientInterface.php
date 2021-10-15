<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\SalesOrder\PostSalesOrder;

use Generated\Shared\Transfer\OrderTransfer;

interface PostSalesOrderClientInterface
{
    /**
     * Post sales order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function postSalesOrder(OrderTransfer $orderTransfer): void;
}
