<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\Util;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;

interface ConditionalEventVisibilityCheckerInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string $eventName
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem, ProcessInterface $process, string $eventName): bool;
}
