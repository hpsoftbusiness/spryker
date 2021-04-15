<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

class GenerateSmsCodeAdapter extends AbstractAdapter
{
    protected const GENERATE_SMS_CODE_ACTION_URI = '/%s/confirmationCodes/sms';

    /**
     * @return string
     */
    public function getUri(): string
    {
        $this->validateRequestTransfer();

        $uri = $this->config->getPaymentSessionActionUri() . static::GENERATE_SMS_CODE_ACTION_URI;

        return sprintf($uri, $this->myWorldApiRequestTransfer->getPaymentCodeGenerateRequest()->getSessionId());
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
        $this->myWorldApiRequestTransfer->requirePaymentCodeGenerateRequest();
        $this->myWorldApiRequestTransfer->getPaymentCodeGenerateRequest()
            ->requireSessionId();
    }
}
