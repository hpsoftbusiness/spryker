<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Writer;

use Generated\Shared\Transfer\PaymentAdyenTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEco\Zed\Adyen\Business\Writer\AdyenWriter as SprykerEcoAdyenWriter;

class AdyenWriter extends SprykerEcoAdyenWriter
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAdyenTransfer
     */
    protected function savePaymentAdyen(
        PaymentTransfer $paymentTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): PaymentAdyenTransfer {
        $paymentAdyenTransfer = (new PaymentAdyenTransfer())
            ->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setPaymentMethod($paymentTransfer->getPaymentSelection())
            ->setReference($paymentTransfer->getAdyenPayment()->getReference())
            ->setSplitMarketplaceReference($paymentTransfer->getAdyenPayment()->getSplitMarketplaceReference())
            ->setSplitCommissionReference($paymentTransfer->getAdyenPayment()->getSplitCommissionReference());

        return $this->entityManager->savePaymentAdyen($paymentAdyenTransfer);
    }
}
