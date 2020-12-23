<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use SprykerEco\Zed\Adyen\Business\AdyenFacade as SprykerEcoAdyenFacade;

/**
 * @method \Pyz\Zed\Adyen\Business\AdyenBusinessFactory getFactory()
 */
class AdyenFacade extends SprykerEcoAdyenFacade implements AdyenFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAdyenPayment(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->expandOrderWithAdyenPayment($orderTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    public function executeRefundCommand(
        array $orderItems,
        OrderTransfer $orderTransfer,
        RefundTransfer $refundTransfer
    ): void {
        $this->getFactory()->createPyzRefundCommandHandler()->handle($orderItems, $orderTransfer, $refundTransfer);
    }
}
