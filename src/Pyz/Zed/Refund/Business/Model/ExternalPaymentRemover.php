<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;

class ExternalPaymentRemover implements ExternalPaymentRemoverInterface
{
    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct(SalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function removeExternalPayments(RefundTransfer $refundTransfer, int $idSalesOrder): void
    {
        $orderTransfer = $this
            ->salesFacade
            ->getOrderByIdSalesOrder($idSalesOrder);

        $externalPayments = $this->getExternalPayments($orderTransfer);

        foreach ($externalPayments as $externalPayment) {
            $refundTransfer->setAmount($refundTransfer->getAmount() - $externalPayment->getAmount());
        }

        foreach ($refundTransfer->getItems() as $item) {
            $refunds = $item->getRefunds();
            foreach ($refunds as $index => $refund) {
                $idSalesPayment = $refund->getFkSalesPayment();
                if ($this->isExternalPayment($idSalesPayment, $externalPayments)) {
                    $refunds->offsetUnset($index);
                }
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    private function getExternalPayments(OrderTransfer $orderTransfer): array
    {
        $externalPayments = [];
        $payments = $orderTransfer->getPayments();
        foreach ($payments as $payment) {
            if ($payment->getPaymentProvider() !== MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                $externalPayments[$payment->getIdSalesPayment()] = $payment;
            }
        }

        return $externalPayments;
    }

    /**
     * @param int $idSalesPayment
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $externalPayments
     *
     * @return bool
     */
    private function isExternalPayment(int $idSalesPayment, array $externalPayments): bool
    {
        return isset($externalPayments[$idSalesPayment]);
    }
}
