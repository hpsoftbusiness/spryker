<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\Finder as SprykerFinder;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class Finder extends SprykerFinder
{
    /**
     * @var \Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface
     */
    private $eventConditionalManualTriggerChecker;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     * @param array $activeProcesses
     * @param \Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface $eventConditionalManualTriggerChecker
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        BuilderInterface $builder,
        array $activeProcesses,
        ConditionalEventVisibilityCheckerInterface $eventConditionalManualTriggerChecker
    ) {
        parent::__construct($queryContainer, $builder, $activeProcesses);

        $this->eventConditionalManualTriggerChecker = $eventConditionalManualTriggerChecker;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return string[]
     */
    protected function getManualEventsByOrderItemEntity(SpySalesOrderItem $orderItem)
    {
        $events = parent::getManualEventsByOrderItemEntity($orderItem);

        $processBuilder = clone $this->builder;
        $process = $processBuilder->createProcess($orderItem->getProcess()->getName());

        $events = array_filter($events, function ($event) use ($orderItem, $process) {
            return $this->eventConditionalManualTriggerChecker->check($orderItem, $process, $event);
        });

        return $events;
    }
}
