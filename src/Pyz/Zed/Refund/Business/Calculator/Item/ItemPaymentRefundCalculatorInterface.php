<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Calculator\Item;

use Generated\Shared\Transfer\ItemTransfer;

interface ItemPaymentRefundCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function calculateItemRefunds(ItemTransfer $itemTransfer, array $paymentTransfers): ItemTransfer;
}
