<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Dependency\Plugin;

use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

interface ItemRefundCalculatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer|null
     */
    public function calculateItemRefund(ItemTransfer $itemTransfer, PaymentTransfer $paymentTransfer): ?ItemRefundTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    public function findApplicablePayment(array $paymentTransfers): ?PaymentTransfer;
}
