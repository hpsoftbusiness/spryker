<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

class ValidateSmsCodeAdapter extends AbstractAdapter
{
    protected const VALIDATE_SMS_CODE_ACTION_URI = '/%s/confirmationCodes/sms/validation';

    /**
     * @return string
     */
    public function getUri(): string
    {
        $this->validateRequestTransfer();

        $uri = $this->config->getPaymentSessionActionUri() . static::VALIDATE_SMS_CODE_ACTION_URI;

        return sprintf($uri, $this->myWorldApiRequestTransfer->getPaymentCodeValidateRequest()->getSessionId());
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return static::HTTP_METHOD_POST;
    }

    /**
     * @return void
     */
    protected function validateRequestTransfer(): void
    {
        $this->myWorldApiRequestTransfer->requirePaymentCodeValidateRequest();
        $this->myWorldApiRequestTransfer->getPaymentCodeValidateRequest()
            ->requireSessionId();
    }
}
