<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Dependency\Plugin;

use Generated\Shared\Transfer\RefundResponseTransfer;

interface RefundProcessorPluginInterface
{
    /**
     * @param array $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer|null
     */
    public function processRefunds(array $refundTransfers): ?RefundResponseTransfer;
}
