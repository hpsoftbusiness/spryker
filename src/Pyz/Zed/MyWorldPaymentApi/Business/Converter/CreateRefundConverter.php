<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Converter;

use Generated\Shared\Transfer\MyWorldApiResponseTransfer;

class CreateRefundConverter extends AbstractConverter
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
        return $responseTransfer;
    }
}
