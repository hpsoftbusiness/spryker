<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Dependency\Plugin;

use Generated\Shared\Transfer\RefundTransfer;

interface RefundValidatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function validate(RefundTransfer $refundTransfer): RefundTransfer;

    /**
     * @return string
     */
    public function getApplicablePaymentProvider(): string;
}
