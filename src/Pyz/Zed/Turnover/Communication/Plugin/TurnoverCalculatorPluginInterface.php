<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Turnover\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TurnoverTransfer;

interface TurnoverCalculatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\TurnoverTransfer $turnoverTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\TurnoverTransfer
     */
    public function calculateTurnover(TurnoverTransfer $turnoverTransfer, OrderTransfer $orderTransfer, array $salesOrderItems): TurnoverTransfer;
}
