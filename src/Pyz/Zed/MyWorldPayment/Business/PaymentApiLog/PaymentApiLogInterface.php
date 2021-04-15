<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\PaymentApiLog;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;

interface PaymentApiLogInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $responseTransfer
     *
     * @return void
     */
    public function save(
        MyWorldApiRequestTransfer $requestTransfer,
        MyWorldApiResponseTransfer $responseTransfer
    ): void;
}
