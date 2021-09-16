<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\OrderStateMachine;

use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReader as SprykerOrderItemManualEventReader;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class OrderItemManualEventReader extends SprykerOrderItemManualEventReader
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    private $omsQueryContainer;

    /**
     * @var \Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface
     */
    private $eventConditionalManualTriggerChecker;

    /**
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $omsQueryContainer
     * @param \Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface $eventConditionalManualTriggerChecker
     */
    public function __construct(
        BuilderInterface $builder,
        OmsQueryContainerInterface $omsQueryContainer,
        ConditionalEventVisibilityCheckerInterface $eventConditionalManualTriggerChecker
    ) {
        parent::__construct($builder);
        $this->omsQueryContainer = $omsQueryContainer;
        $this->eventConditionalManualTriggerChecker = $eventConditionalManualTriggerChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     *
     * @return string[]
     */
    protected function getManualEventsByOrderItem(ItemTransfer $orderItemTransfer): array
    {
        $events = parent::getManualEventsByOrderItem($orderItemTransfer);
        $orderItem = $this
            ->omsQueryContainer
            ->querySalesOrderItems([$orderItemTransfer->getIdSalesOrderItem()])
            ->findOne();
        $processBuilder = clone $this->builder;
        $process = $processBuilder->createProcess($orderItem->getProcess()->getName());

        $events = array_filter($events, function ($event) use ($orderItem, $process) {
            return $this->eventConditionalManualTriggerChecker->check($orderItem, $process, $event);
        });

        return $events;
    }
}
