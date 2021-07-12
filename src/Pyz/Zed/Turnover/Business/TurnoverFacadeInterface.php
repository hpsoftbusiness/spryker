<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Turnover\Business;

use Generated\Shared\Transfer\TurnoverTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface TurnoverFacadeInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\TurnoverTransfer
     */
    public function calculateTurnover(array $salesOrderItems, SpySalesOrder $salesOrderEntity): TurnoverTransfer;
}
