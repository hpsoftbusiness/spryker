<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Business\Model\RefundCalculatorInterface as SprykerRefundCalculatorInterface;

interface RefundCalculatorInterface extends SprykerRefundCalculatorInterface
{
    /**
     * @param array $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefundWithoutExternalPayment(array $salesOrderItems, SpySalesOrder $salesOrderEntity): RefundTransfer;
}
