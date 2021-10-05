<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;

interface ExternalPaymentRemoverInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param int $idSalesOrder
     *
     * @return mixed
     */
    public function removeExternalPayments(RefundTransfer $refundTransfer, int $idSalesOrder);
}
