<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface as SprykerPaymentRepositoryInterface;

interface PaymentRepositoryInterface extends SprykerPaymentRepositoryInterface
{
    /**
     * @param string $paymentMethodKey
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function findPaymentMethodByKey(string $paymentMethodKey): ?PaymentMethodTransfer;
}
