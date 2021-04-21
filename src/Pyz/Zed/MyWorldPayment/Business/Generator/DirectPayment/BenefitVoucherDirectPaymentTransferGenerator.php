<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\QuoteTransfer;

class BenefitVoucherDirectPaymentTransferGenerator extends AbstractDirectPaymentTransferGenerator
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isPaymentUsed(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getTotalUsedBenefitVouchersAmount() > 0;
    }

    /**
     * @return int
     */
    protected function getPaymentOptionId(): int
    {
        return $this->config->getOptionBenefitVoucher();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getAmount(QuoteTransfer $quoteTransfer): int
    {
        return (int)$quoteTransfer->getTotalUsedBenefitVouchersAmount();
    }
}
