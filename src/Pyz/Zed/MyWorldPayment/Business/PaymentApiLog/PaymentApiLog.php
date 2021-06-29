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

        /**
         * TODO: we can't identiy type of call just based on the request object.
         * It would be better to refactor this part and use type directly as an input param
         */
        if ($requestTransfer->getPaymentCodeValidateRequest()) {
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
        } elseif ($requestTransfer->getPaymentSessionRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentSessionRequest());
            $response = $this->abstractTransferToString($responseTransfer->getPaymentSessionResponse());
            $type = self::PAYMENT_SESSION_TYPE;
        } elseif ($requestTransfer->getPaymentCodeGenerateRequest()) {
            $request = $this->abstractTransferToString($requestTransfer->getPaymentCodeGenerateRequest());
            $type = self::PAYMENT_CODE_GENERATE_TYPE;
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
            $requestData = $this->normalizeArray($abstractTransfer->toArray());

            return json_encode($requestData);
        }

        return null;
    }

    /**
     * Specification:
     * - Due to https://bugs.php.net/bug.php?id=72567 bug json_encode float serialization precision is set to 14.
     * Converting float to string prevents incorrect rounding of float values.
     *
     * @param array $data
     *
     * @return array
     */
    private function normalizeArray(array $data): array
    {
        return array_map(
            function ($value) {
                if (is_array($value)) {
                    return $this->normalizeArray($value);
                }

                if (is_float($value)) {
                    return (string)$value;
                }

                return $value;
            },
            $data,
        );
    }
}
