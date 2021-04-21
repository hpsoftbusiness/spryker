<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Orm\Zed\MyWorldPayment\Persistence\Map\PyzPaymentMyWorldTableMap;
use Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld;

class MyWorldPaymentMapper
{
    private const STATUS_MAP = [
        'New' => PyzPaymentMyWorldTableMap::COL_STATUS_NEW,
        'Charged' => PyzPaymentMyWorldTableMap::COL_STATUS_CHARGED,
        'Refund in progress' => PyzPaymentMyWorldTableMap::COL_STATUS_REFUND_IN_PROGRESS,
        'PartialRefund successful' => PyzPaymentMyWorldTableMap::COL_STATUS_PARTIAL_REFUND_SUCCESSFUL,
    ];

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld $paymentMyWorldEntity
     *
     * @return \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld
     */
    public function mapPaymentDataResponseTransferToEntity(
        PaymentDataResponseTransfer $paymentDataResponseTransfer,
        PyzPaymentMyWorld $paymentMyWorldEntity
    ): PyzPaymentMyWorld {
        $paymentMyWorldEntity->setAmount($paymentDataResponseTransfer->getAmount());
        $paymentMyWorldEntity->setCurrencyId($paymentDataResponseTransfer->getCurrencyId());
        $paymentMyWorldEntity->setPaymentId($paymentDataResponseTransfer->getPaymentId());
        $paymentMyWorldEntity->setReference($paymentDataResponseTransfer->getReference());

        $status = $this->getStatusForPersisting($paymentDataResponseTransfer->getStatus());
        if ($status) {
            $paymentMyWorldEntity->setStatus($status);
        }

        return $paymentMyWorldEntity;
    }

    /**
     * @param \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld $paymentMyWorldEntity
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentDataResponseTransfer
     */
    public function mapEntityToPaymentDataResponseTransfer(
        PyzPaymentMyWorld $paymentMyWorldEntity,
        PaymentDataResponseTransfer $paymentDataResponseTransfer
    ): PaymentDataResponseTransfer {
        $paymentDataResponseTransfer = $paymentDataResponseTransfer->fromArray($paymentMyWorldEntity->toArray(), true);
        $status = $this->getStatusForPresentation($paymentMyWorldEntity->getStatus());
        $paymentDataResponseTransfer->setStatus($status);

        return $paymentDataResponseTransfer;
    }

    /**
     * @param string $status
     *
     * @return string|null
     */
    private function getStatusForPersisting(string $status): ?string
    {
        return self::STATUS_MAP[$status] ?? null;
    }

    /**
     * @param string $status
     *
     * @return string|null
     */
    private function getStatusForPresentation(string $status): ?string
    {
        return array_flip(self::STATUS_MAP)[$status] ?? null;
    }
}
