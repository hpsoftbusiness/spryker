<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\Refund;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;

interface PaymentRefundRequestTransferGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function generate(PaymentDataResponseTransfer $paymentDataResponseTransfer, array $refundTransfers): MyWorldApiRequestTransfer;
}
