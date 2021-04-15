<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Converter;

use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Psr\Http\Message\ResponseInterface;

interface MyWorldPaymentApiConverterInterface
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function convertToResponseTransfer(ResponseInterface $response, $isSuccess = true): MyWorldApiResponseTransfer;
}
