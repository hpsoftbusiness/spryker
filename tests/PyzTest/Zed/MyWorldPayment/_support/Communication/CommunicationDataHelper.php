<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Communication;

use Codeception\Module;
use Generated\Shared\DataBuilder\PaymentDataResponseBuilder;
use Generated\Shared\DataBuilder\RefundBuilder;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\MyWorldPayment\Persistence\Map\PyzPaymentMyWorldTableMap;
use Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld;

class CommunicationDataHelper extends Module
{
    public const PAYMENT_OPTION_ID_E_VOUCHER = 1;
    public const PAYMENT_OPTION_ID_E_VOUCHER_MARKETER = 2;
    public const PAYMENT_OPTION_ID_CASHBACK = 6;
    public const PAYMENT_OPTION_ID_BENEFIT_VOUCHER = 10;

    public const PAYMENT_DATA_DEFAULT_DATA = [
        PaymentDataResponseTransfer::PAYMENT_ID => 'payment_id',
        PaymentDataResponseTransfer::AMOUNT => 1230,
        PaymentDataResponseTransfer::REFERENCE => 'reference_string',
        PaymentDataResponseTransfer::CURRENCY_ID => 'EUR',
        PaymentDataResponseTransfer::STATUS => PyzPaymentMyWorldTableMap::COL_STATUS_CHARGED,
    ];

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentDataResponseTransfer
     */
    public function buildPaymentDataResponseTransfer(array $override = []): PaymentDataResponseTransfer
    {
        $override = array_merge(
            self::PAYMENT_DATA_DEFAULT_DATA,
            $override
        );

        return (new PaymentDataResponseBuilder($override))->build();
    }

    /**
     * @param int $idSalesOrder
     * @param array $override
     *
     * @return \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorld
     */
    public function createMyWorldPaymentEntity(int $idSalesOrder, array $override = []): PyzPaymentMyWorld
    {
        $paymentDataResponseTransfer = $this->buildPaymentDataResponseTransfer($override);
        $paymentEntity = new PyzPaymentMyWorld();
        $paymentEntity->fromArray($paymentDataResponseTransfer->toArray());
        $paymentEntity->setFkSalesOrder($idSalesOrder);
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function buildRefundTransfer(array $overrideData): RefundTransfer
    {
        return (new RefundBuilder($overrideData))->build();
    }
}
