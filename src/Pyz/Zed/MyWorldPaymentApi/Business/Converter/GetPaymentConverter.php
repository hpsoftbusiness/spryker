<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Converter;

use ArrayObject;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\PaymentTransactionTransfer;

class GetPaymentConverter extends AbstractConverter
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function updateResponseTransfer(
        MyWorldApiResponseTransfer $responseTransfer,
        array $response
    ): MyWorldApiResponseTransfer {
        $paymentId = $response['Data']['PaymentId'] ?? null;
        $reference = $response['Data']['Reference'] ?? null;
        $currencyId = $response['Data']['CurrencyID'] ?? null;
        $amount = $response['Data']['Amount'] ?? null;
        $status = $response['Data']['Status'] ?? null;
        $transactions = $this->transformTransaction($response['Data']['Transactions'] ?? []);
        $paymentDataResponseTransfer = new PaymentDataResponseTransfer();
        $paymentDataResponseTransfer->setPaymentId($paymentId);
        $paymentDataResponseTransfer->setReference($reference);
        $paymentDataResponseTransfer->setCurrencyId($currencyId);
        $paymentDataResponseTransfer->setAmount($amount);
        $paymentDataResponseTransfer->setStatus($status);

        $paymentDataResponseTransfer->setTransactions($transactions);

        $responseTransfer->setPaymentDataResponse($paymentDataResponseTransfer);

        return $responseTransfer;
    }

    /**
     * @param array $transactionArray
     *
     * @return \ArrayObject
     */
    private function transformTransaction(array $transactionArray): ArrayObject
    {
        $transactionArrayObject = new ArrayObject();

        foreach ($transactionArray as $transaction) {
            $transactionTransform = new PaymentTransactionTransfer();
            $transactionTransform->setPaymentOptionId($transaction['PaymentOptionId']);
            $transactionTransform->setAmount($transaction['Amount']);
            $transactionTransform->setUnit($transaction['Unit']);
            $transactionTransform->setUnitType($transaction['UnitType']);
            $transactionTransform->setDateTime($transaction['DateTime']);
            $transactionTransform->setBatchNumber($transaction['BatchNumber']);
            $transactionTransform->setStatus($transaction['Status']);
            $transactionTransform->setStatusCode($transaction['StatusCode']);

            $transactionArrayObject->append($transactionTransform);
        }

        return $transactionArrayObject;
    }
}
