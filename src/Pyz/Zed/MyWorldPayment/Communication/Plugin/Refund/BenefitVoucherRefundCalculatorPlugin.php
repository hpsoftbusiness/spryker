<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund;

use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\Refund\Calculator\ItemRefundCalculatorTrait;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

/**
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class BenefitVoucherRefundCalculatorPlugin extends AbstractMyWorldRefundCalculatorPlugin
{
    use ItemRefundCalculatorTrait;

    protected const APPLICABLE_PAYMENT_METHOD = MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer|null
     */
    public function calculateItemRefund(ItemTransfer $itemTransfer, PaymentTransfer $paymentTransfer): ?ItemRefundTransfer
    {
        if (!$itemTransfer->getUseBenefitVoucher()) {
            return null;
        }

        $refundAmount = $itemTransfer->getTotalUsedBenefitVouchersAmountOrFail();
        $paymentTransfer->setRefundableAmount($paymentTransfer->getRefundableAmount() - $refundAmount);

        return $this->createItemRefundTransfer(
            $itemTransfer->getIdSalesOrderItem(),
            $paymentTransfer->getIdSalesPayment(),
            $refundAmount
        );
    }
}
