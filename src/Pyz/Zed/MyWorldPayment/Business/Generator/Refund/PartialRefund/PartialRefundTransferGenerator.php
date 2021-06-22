<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund;

use Generated\Shared\Transfer\PartialRefundTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldRefundException;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

class PartialRefundTransferGenerator implements PartialRefundTransferGeneratorInterface
{
    private const EXCEPTION_MESSAGE_UNKNOWN_PAYMENT_METHOD = 'Failed to map payment method %s to payment option ID.';

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    private $config;

    /**
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $config
     */
    public function __construct(MyWorldPaymentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     * @param string $currencyCode
     *
     * @throws \Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldRefundException
     *
     * @return \Generated\Shared\Transfer\PartialRefundTransfer[]
     */
    public function generate(array $refundTransfers, string $currencyCode): array
    {
        $partialRefundTransfers = [];
        foreach ($refundTransfers as $refundTransfer) {
            $paymentTransfer = $refundTransfer->getPayment();
            $paymentOptionId = $this->getPaymentOptionByMethodName($paymentTransfer->getPaymentMethod());
            if ($paymentOptionId === null) {
                throw new MyWorldRefundException(
                    sprintf(self::EXCEPTION_MESSAGE_UNKNOWN_PAYMENT_METHOD, $paymentTransfer->getPaymentMethod())
                );
            }

            $partialRefundTransfer = $this->buildPartialRefundTransfer(
                $refundTransfer,
                $currencyCode,
                $paymentOptionId
            );

            if ($paymentTransfer->getPaymentMethod() === MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME) {
                $partialRefundTransfer->setMaxAllowedAmount(
                    $this->calculateMaxAvailableAmount($refundTransfer->getItems())
                );
            }

            $partialRefundTransfers[] = $partialRefundTransfer;
        }

        return $partialRefundTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param string $currencyCode
     * @param int $paymentOptionId
     *
     * @return \Generated\Shared\Transfer\PartialRefundTransfer
     */
    private function buildPartialRefundTransfer(
        RefundTransfer $refundTransfer,
        string $currencyCode,
        int $paymentOptionId
    ): PartialRefundTransfer {
        $partialRefundTransfer = new PartialRefundTransfer();
        $partialRefundTransfer->setPaymentOptionId($paymentOptionId);
        $partialRefundTransfer->setAmount($refundTransfer->getAmount());
        $partialRefundTransfer->setUnit($currencyCode);
        $partialRefundTransfer->setUnitType($this->config->getUnitTypeCurrency());

        return $partialRefundTransfer;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int
     */
    private function calculateMaxAvailableAmount(iterable $itemTransfers): int
    {
        $maxAvailableAmount = 0;
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher()) {
                $maxAvailableAmount += $itemTransfer->getBenefitVoucherDealData()->getAmount();
            }
        }

        return $maxAvailableAmount;
    }

    /**
     * @param string $paymentMethodName
     *
     * @return int|null
     */
    private function getPaymentOptionByMethodName(string $paymentMethodName): ?int
    {
        return $this->config->getMapOptionNameToOptionId()[$paymentMethodName] ?? null;
    }
}
