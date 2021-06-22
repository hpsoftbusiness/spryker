<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DummyPrepayment\Communication\Plugin\Refund;

use Generated\Shared\Transfer\RefundResponseTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class DummyPrepaymentRefundProcessorPlugin extends AbstractPlugin implements RefundProcessorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer|null
     */
    public function processRefunds(array $refundTransfers): ?RefundResponseTransfer
    {
        $dummyPrepaymentRefundTransfer = $this->findRefundTransfer($refundTransfers);
        if (!$dummyPrepaymentRefundTransfer) {
            return null;
        }

        $this->setItemRefundsStatus($dummyPrepaymentRefundTransfer);
        $this->setExpenseRefundsStatus($dummyPrepaymentRefundTransfer);

        return (new RefundResponseTransfer())
            ->setIsSuccess(true)
            ->addRefund($dummyPrepaymentRefundTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    private function setItemRefundsStatus(RefundTransfer $refundTransfer): void
    {
        foreach ($refundTransfer->getItemRefunds() as $itemRefund) {
            $itemRefund->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_PENDING);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    private function setExpenseRefundsStatus(RefundTransfer $refundTransfer): void
    {
        foreach ($refundTransfer->getExpenseRefunds() as $expenseRefund) {
            $expenseRefund->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_PENDING);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundTransfer|null
     */
    private function findRefundTransfer(array $refundTransfers): ?RefundTransfer
    {
        foreach ($refundTransfers as $refundTransfer) {
            if ($refundTransfer->getPayment()->getPaymentMethod() === DummyPrepaymentConfig::DUMMY_PREPAYMENT) {
                return $refundTransfer;
            }
        }

        return null;
    }
}
