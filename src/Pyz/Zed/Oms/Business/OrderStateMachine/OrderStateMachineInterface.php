<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface as SprykerOrderStateMachineInterface;

interface OrderStateMachineInterface extends SprykerOrderStateMachineInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Pyz\Zed\Oms\Business\Process\EventInterface $transition
     *
     * @return bool
     */
    public function checkConditionForEvent(SpySalesOrderItem $orderItem, EventInterface $transition): bool;
}
