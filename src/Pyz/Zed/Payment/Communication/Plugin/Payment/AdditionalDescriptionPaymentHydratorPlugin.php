<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Communication\Plugin\Payment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface;

class AdditionalDescriptionPaymentHydratorPlugin implements PaymentHydratorPluginInterface
{
    /**
     * @inheritDoc
     */
    public function hydrate(OrderTransfer $orderTransfer, PaymentTransfer $paymentTransfer): PaymentTransfer
    {
        $paymentTransfer->setAdditionalInformation('dummyPrepayment.prepaymentbank-info');

        return $paymentTransfer;
    }
}
