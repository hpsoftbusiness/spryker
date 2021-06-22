<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund;

use Spryker\Zed\Refund\RefundConfig as SprykerRefundConfig;

class RefundConfig extends SprykerRefundConfig
{
    public const PAYMENT_REFUND_STATUS_NEW = 'new';
    public const PAYMENT_REFUND_STATUS_PENDING = 'pending';
    public const PAYMENT_REFUND_STATUS_PROCESSED = 'processed';
    public const PAYMENT_REFUND_STATUS_FAILED = 'failed';

    public const REFUND_DETAIL_TYPE_ITEM = 'item';
    public const REFUND_DETAIL_TYPE_EXPENSE = 'expense';
}
