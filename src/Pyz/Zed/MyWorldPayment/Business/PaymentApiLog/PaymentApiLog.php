<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\PaymentApiLog;

use Generated\Shared\Transfer\MyWorldApiErrorResponseTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldApiLog;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Throwable;

class PaymentApiLog implements PaymentApiLogInterface
{
    use LoggerTrait;

    private const PAYMENT_SESSION_TYPE = 'payment-session';
    private const PAYMENT_CODE_GENERATE_TYPE = 'payment-code-generate';
    private const PAYMENT_CODE_VALIDATE_TYPE = 'payment-code-validate';
    private const PAYMENT_CONFIRMATION_TYPE = 'payment-confirmation';
    private const PAYMENT_REFUND_TYPE = 'payment-refund';
    private const PAYMENT_DATA_TYPE = 'payment-data';

    /**
     * @inheritDoc
     */
    public function save(
        MyWorldApiRequestTransfer $requestTransfer,
        MyWorldApiResponseTransfer $responseTransfer
    ): void {
        try {
            $apiLog = new PyzPaymentMyWorldApiLog();
            $apiLog->setIsSuccess($responseTransfer->getIsSuccess());

            if (!$responseTransfer->getIsSuccess()) {
                $apiLog = $this->setError($apiLog, $responseTransfer->getError());
            }
            $apiLog = $this->setRequestResponse($apiLog, $requestTransfer, $responseTransfer);
            $apiLog->save();
        } catch (Throwable $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }

    /**
     * @param \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldApiLog $apiLog
     * @param \Generated\Shared\Transfer\MyWorldApiErrorResponseTransfer $errorResponseTransfer
     *
     * @return \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldApiLog
     */
    private function setError(
        PyzPaymentMyWorldApiLog $apiLog,
        MyWorldApiErrorResponseTransfer $errorResponseTransfer
    ): PyzPaymentMyWorldApiLog {
        $apiLog->setStatusCode($errorResponseTransfer->getStatusCode());
        $apiLog->setErrorCode($errorResponseTransfer->getErrorCode());
        $apiLog->setErrorMessage($errorResponseTransfer->getErrorMessage());

        return $apiLog;
    }

    /**
     * @param \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldApiLog $apiLog
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $responseTransfer
     *
     * @return \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldApiLog
     */
    private function setRequestResponse(
        PyzPaymentMyWorldApiLog $apiLog,
        MyWorldApiRequestTransfer $requestTransfer,
        MyWorldApiResponseTransfer $responseTransfer
    ): PyzPaymentMyWorldApiLog {
        $type = null;
        $request = null;
        $response = null;

        if ($requestTransfer->getPaymentSessionRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentSessionRequest());
            $response = $this->abstractTransferToString($responseTransfer->getPaymentSessionResponse());
            $type = self::PAYMENT_SESSION_TYPE;
        } elseif ($requestTransfer->getPaymentCodeGenerateRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentCodeGenerateRequest());
            $type = self::PAYMENT_CODE_GENERATE_TYPE;
        } elseif ($requestTransfer->getPaymentCodeValidateRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentCodeValidateRequest());
            $response = $this->abstractTransferToString($responseTransfer->getPaymentCodeValidateResponse());
            $type = self::PAYMENT_CODE_VALIDATE_TYPE;
        } elseif ($requestTransfer->getPaymentConfirmationRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentConfirmationRequest());
            $response = $this->abstractTransferToString($responseTransfer->getPaymentConfirmationResponseTransfer());
            $type = self::PAYMENT_CONFIRMATION_TYPE;
        } elseif ($requestTransfer->getPaymentRefundRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentRefundRequest());
            $type = self::PAYMENT_REFUND_TYPE;
        } elseif ($requestTransfer->getPaymentDataRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentDataRequest());
            $response = $this->abstractTransferToString($responseTransfer->getPaymentDataResponse());
            $type = self::PAYMENT_DATA_TYPE;
        }

        $apiLog->setRequest($request);
        $apiLog->setResponse($response);
        $apiLog->setType($type);

        return $apiLog;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $abstractTransfer
     *
     * @return string|null
     */
    private function abstractTransferToString(?AbstractTransfer $abstractTransfer): ?string
    {
        if ($abstractTransfer) {
            return json_encode($abstractTransfer->toArray());
        }

        return null;
    }
}
