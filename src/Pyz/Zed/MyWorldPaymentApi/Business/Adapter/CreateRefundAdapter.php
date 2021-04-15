<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

class CreateRefundAdapter extends AbstractAdapter
{
    protected const REFUND_ACTION_URI = '/refund';

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->config->getPaymentActionUrl() . static::REFUND_ACTION_URI;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return static::HTTP_METHOD_POST;
    }
}
