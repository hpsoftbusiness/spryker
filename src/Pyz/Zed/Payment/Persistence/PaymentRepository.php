<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Payment\Persistence\PaymentRepository as SprykerPaymentRepository;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentPersistenceFactory getFactory()
 */
class PaymentRepository extends SprykerPaymentRepository implements PaymentRepositoryInterface
{
    /**
     * @param string $paymentMethodKey
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function findPaymentMethodByKey(string $paymentMethodKey): ?PaymentMethodTransfer
    {
        $paymentMethodEntity = $this->getFactory()
            ->createPaymentMethodQuery()
            ->filterByPaymentMethodKey($paymentMethodKey)
            ->findOne();

        if ($paymentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityToPaymentMethodTransfer($paymentMethodEntity, new PaymentMethodTransfer());
    }
}
