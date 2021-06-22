<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Refund;

interface RefundProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundsTransfer
     *
     * @return void
     */
    public function processRefunds(array $refundsTransfer): void;
}
