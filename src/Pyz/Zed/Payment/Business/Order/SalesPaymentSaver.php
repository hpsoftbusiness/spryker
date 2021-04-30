<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Business\Order;

use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Spryker\Zed\Payment\Business\Order\SalesPaymentSaver as SprykerSalesPaymentSaver;

class SalesPaymentSaver extends SprykerSalesPaymentSaver
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    protected function mapSalesPaymentEntity(PaymentTransfer $paymentTransfer, $idSalesOrder): SpySalesPayment
    {
        $salesPaymentEntity = parent::mapSalesPaymentEntity($paymentTransfer, $idSalesOrder);

        if ($paymentTransfer->getIsLimitedAmount()) {
            $salesPaymentEntity->setIsLimitedAmount($paymentTransfer->getIsLimitedAmount());
            $salesPaymentEntity->setAvailableAmount($paymentTransfer->getAvailableAmount());
        }

        return $salesPaymentEntity;
    }
}
