<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\Util;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;

class ConditionalEventVisibilityChecker implements ConditionalEventVisibilityCheckerInterface
{
    /**
     * @var \Pyz\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    private $orderStateMachine;

    /**
     * @param \Pyz\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface $orderStateMachine
     */
    public function __construct(OrderStateMachineInterface $orderStateMachine)
    {
        $this->orderStateMachine = $orderStateMachine;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string $eventName
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem, ProcessInterface $process, string $eventName): bool
    {
        /** @var \Pyz\Zed\Oms\Business\Process\EventInterface $event */
        foreach ($process->getManualEvents() as $event) {
            if ($event->getName() === $eventName) {
                return $this->orderStateMachine->checkConditionForEvent($orderItem, $event);
            }
        }

        return true;
    }
}
