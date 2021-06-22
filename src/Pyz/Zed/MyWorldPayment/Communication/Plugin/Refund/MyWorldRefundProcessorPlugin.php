<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund;

use ArrayObject;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldRefundException;
use Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class MyWorldRefundProcessorPlugin extends AbstractPlugin implements RefundProcessorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer|null
     */
    public function processRefunds(array $refundTransfers): ?RefundResponseTransfer
    {
        $myWorldRefundTransfers = $this->filterMyWorldRefunds($refundTransfers);
        if (empty($myWorldRefundTransfers)) {
            return null;
        }

        $refundResponseTransfer = (new RefundResponseTransfer())->setRefunds(new ArrayObject($myWorldRefundTransfers));
        try {
            $this->getFacade()->processRefunds($myWorldRefundTransfers);
            $refundResponseTransfer->setIsSuccess(true);
        } catch (MyWorldRefundException $exception) {
            $refundResponseTransfer->setIsSuccess(false);
        }

        $this->setRefundResponseStatus($refundResponseTransfer);

        return $refundResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundResponseTransfer $refundResponseTransfer
     *
     * @return void
     */
    private function setRefundResponseStatus(RefundResponseTransfer $refundResponseTransfer): void
    {
        $status = $refundResponseTransfer->getIsSuccess()
            ? RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED
            : RefundConfig::PAYMENT_REFUND_STATUS_FAILED;

        foreach ($refundResponseTransfer->getRefunds() as $refundTransfer) {
            $this->setItemRefundsStatus($refundTransfer, $status);
            $this->setExpenseRefundsStatus($refundTransfer, $status);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param string $status
     *
     * @return void
     */
    private function setItemRefundsStatus(RefundTransfer $refundTransfer, string $status): void
    {
        foreach ($refundTransfer->getItemRefunds() as $itemRefund) {
            $itemRefund->setStatus($status);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param string $status
     *
     * @return void
     */
    private function setExpenseRefundsStatus(RefundTransfer $refundTransfer, string $status): void
    {
        foreach ($refundTransfer->getExpenseRefunds() as $expenseRefund) {
            $expenseRefund->setStatus($status);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundsTransfers
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    private function filterMyWorldRefunds(array $refundsTransfers): array
    {
        return array_filter(
            $refundsTransfers,
            static function (RefundTransfer $refundTransfer) {
                $payment = $refundTransfer->getPayment();

                return $payment->getPaymentProvider() === MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD;
            }
        );
    }
}
