<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\Refund;

use ArrayObject;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PartialRefundTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\PaymentRefundRequestTransfer;
use Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund\PartialRefundTransferGeneratorInterface;

class PaymentRefundRequestTransferGenerator implements PaymentRefundRequestTransferGeneratorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund\PartialRefundTransferGeneratorInterface
     */
    private $partialRefundTransferGenerator;

    /**
     * @param \Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund\PartialRefundTransferGeneratorInterface $partialRefundTransferGenerator
     */
    public function __construct(PartialRefundTransferGeneratorInterface $partialRefundTransferGenerator)
    {
        $this->partialRefundTransferGenerator = $partialRefundTransferGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function generate(PaymentDataResponseTransfer $paymentDataResponseTransfer, array $refundTransfers): MyWorldApiRequestTransfer
    {
        $partialRefundTransfers = $this->partialRefundTransferGenerator->generate(
            $refundTransfers,
            $paymentDataResponseTransfer->getCurrencyId()
        );
        $totalRefundAmount = $this->calculateTotalRefundAmount($partialRefundTransfers);
        $paymentRefundRequestTransfer = (new PaymentRefundRequestTransfer())
            ->setAmount($totalRefundAmount)
            ->setPaymentId($paymentDataResponseTransfer->getPaymentId())
            ->setCurrency($paymentDataResponseTransfer->getCurrencyId())
            ->setPartialRefunds(new ArrayObject($partialRefundTransfers));

        return (new MyWorldApiRequestTransfer())->setPaymentRefundRequest($paymentRefundRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PartialRefundTransfer[] $partialRefundTransfers
     *
     * @return int
     */
    private function calculateTotalRefundAmount(array $partialRefundTransfers): int
    {
        return array_reduce(
            $partialRefundTransfers,
            static function (int $totalRefundAmount, PartialRefundTransfer $partialRefundTransfer): int {
                return $totalRefundAmount + $partialRefundTransfer->getAmount();
            },
            0
        );
    }
}
