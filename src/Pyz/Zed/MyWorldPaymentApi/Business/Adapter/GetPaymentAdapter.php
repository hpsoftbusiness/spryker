<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

class GetPaymentAdapter extends AbstractAdapter
{
    /**
     * @return string
     */
    public function getUri(): string
    {
        return sprintf($this->config->getPaymentActionUrl() . '/%s', $this->myWorldApiRequestTransfer->getPaymentDataRequest()->getPaymentId());
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return static::HTTP_METHOD_GET;
    }

    /**
     * @return void
     */
    protected function validateRequestTransfer(): void
    {
        $this->myWorldApiRequestTransfer->requirePaymentDataRequest();
        $this->myWorldApiRequestTransfer->getPaymentDataRequest()
            ->requirePaymentId();
    }
}
