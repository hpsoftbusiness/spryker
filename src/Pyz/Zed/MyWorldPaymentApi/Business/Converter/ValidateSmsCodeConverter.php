<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Converter;

use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateResponseTransfer;

class ValidateSmsCodeConverter extends AbstractConverter
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
        $description = $response['Data']['Description'] ?? null;
        $isValid = $response['Data']['IsValid'] ?? false;
        $paymentCodeValidateResponse = new PaymentCodeValidateResponseTransfer();
        $paymentCodeValidateResponse->setDescription($description);
        $paymentCodeValidateResponse->setIsValid($isValid);

        $responseTransfer->setPaymentCodeValidateResponse($paymentCodeValidateResponse);

        return $responseTransfer;
    }
}
