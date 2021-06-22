<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Propel\Runtime\Collection\Collection;

class PaymentMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection|\Orm\Zed\Payment\Persistence\SpySalesPayment[] $spySalesPayments
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    public function mapSalesPaymentEntityCollectionToTransfers(Collection $spySalesPayments): array
    {
        $paymentTransfers = [];
        foreach ($spySalesPayments as $salesPaymentEntity) {
            $paymentTransfers[] = $this->mapSalesPaymentEntityToTransfer($salesPaymentEntity, new PaymentTransfer());
        }

        return $paymentTransfers;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpySalesPayment $spySalesPayment
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function mapSalesPaymentEntityToTransfer(
        SpySalesPayment $spySalesPayment,
        PaymentTransfer $paymentTransfer
    ): PaymentTransfer {
        $paymentTransfer->fromArray($spySalesPayment->getSalesPaymentMethodType()->toArray(), true);
        $paymentTransfer->fromArray($spySalesPayment->toArray(), true);

        return $paymentTransfer;
    }
}
