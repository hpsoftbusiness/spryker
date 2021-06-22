<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund;

use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class CashbackRefundCalculatorPlugin extends AbstractCashbackRefundCalculatorPlugin
{
    protected const APPLICABLE_PAYMENT_METHOD = MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME;
}
