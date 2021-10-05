<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine as SprykerOrderStateMachine;

class OrderStateMachine extends SprykerOrderStateMachine implements OrderStateMachineInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Pyz\Zed\Oms\Business\Process\EventInterface $event
     *
     * @return bool
     */
    public function checkConditionForEvent(SpySalesOrderItem $orderItem, EventInterface $event): bool
    {
        if (!$event->getCondition()) {
            return true;
        }

        return $this->getCondition($event->getCondition())->check($orderItem);
    }

    /**
     * @param string $eventId
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param array|\Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function triggerEvent($eventId, array $orderItems, $data)
    {
        $processes = $this->getProcesses($orderItems);
        $orderItems = $this->filterAffectedOrderItems($eventId, $orderItems, $processes);
        $orderItems = array_filter($orderItems, function (SpySalesOrderItem $orderItem) use ($processes, $eventId) {
            $stateId = $orderItem->getState()->getName();
            $processId = $orderItem->getProcess()->getName();
            $process = $processes[$processId];
            $state = $process->getStateFromAllProcesses($stateId);
            /** @var \Pyz\Zed\Oms\Business\Process\EventInterface $event */
            $event = $state->getEvent($eventId);

            return $this->checkConditionForEvent($orderItem, $event);
        });

        return parent::triggerEvent($eventId, $orderItems, $data);
    }
}
