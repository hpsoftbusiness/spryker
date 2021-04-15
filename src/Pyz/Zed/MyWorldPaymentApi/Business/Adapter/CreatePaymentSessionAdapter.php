<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

use GuzzleHttp\RequestOptions;

class CreatePaymentSessionAdapter extends AbstractAdapter
{
    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->config->getPaymentSessionActionUri();
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return static::HTTP_METHOD_POST;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function prepareHeaders(array $options): array
    {
        $options = parent::prepareHeaders($options);
        $options[RequestOptions::HEADERS][static::HEADER_MWS_IDENTITY_TOKEN_KEY] = $this->getMwsIdentityToken();

        return $options;
    }
}
