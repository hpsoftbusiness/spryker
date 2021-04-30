<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

use Psr\Http\Message\ResponseInterface;

interface MyWorldPaymentApiAdapterInterface
{
    /**
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendRequest(array $data): ResponseInterface;

    /**
     * @return $this
     */
    public function allowUsingStubToken();
}
