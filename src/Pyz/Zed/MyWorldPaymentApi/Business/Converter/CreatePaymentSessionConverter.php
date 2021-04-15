<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Converter;

use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentSessionResponseTransfer;

class CreatePaymentSessionConverter extends AbstractConverter
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
        $sessionId = $response['Data']['SessionId'] ?? null;
        $twoFactorAuth = $response['Data']['TwoFactorAuth'] ?? [];

        $paymentSessionResponse = new PaymentSessionResponseTransfer();
        $paymentSessionResponse->setSessionId($sessionId);
        $paymentSessionResponse->setTwoFactorAuth($twoFactorAuth);
        $responseTransfer->setPaymentSessionResponse($paymentSessionResponse);

        return $responseTransfer;
    }
}
