<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DummyPrepayment\Communication\Plugin\Refund;

use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Zed\Refund\Dependency\Plugin\RefundValidatorPluginInterface;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class DummyPrepaymentRefundValidatorPlugin extends AbstractPlugin implements RefundValidatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function validate(RefundTransfer $refundTransfer): RefundTransfer
    {
        foreach ($refundTransfer->getItemRefunds() as $itemRefundTransfer) {
            $itemRefundTransfer->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED);
        }

        foreach ($refundTransfer->getExpenseRefunds() as $expenseRefundTransfer) {
            $expenseRefundTransfer->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED);
        }

        return $refundTransfer;
    }

    /**
     * @return string
     */
    public function getApplicablePaymentProvider(): string
    {
        return DummyPrepaymentConfig::DUMMY_PREPAYMENT;
    }
}
