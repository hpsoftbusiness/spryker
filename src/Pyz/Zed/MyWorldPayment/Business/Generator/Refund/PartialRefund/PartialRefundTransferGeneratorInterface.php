<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\Refund\PartialRefund;

interface PartialRefundTransferGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     * @param string $currencyCode
     *
     * @return \Generated\Shared\Transfer\PartialRefundTransfer[]
     */
    public function generate(array $refundTransfers, string $currencyCode): array;
}
