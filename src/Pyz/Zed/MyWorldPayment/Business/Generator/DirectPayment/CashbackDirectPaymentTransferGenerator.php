<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\QuoteTransfer;

class CashbackDirectPaymentTransferGenerator extends AbstractDirectPaymentTransferGenerator
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isPaymentUsed(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getUseCashbackBalance() && $quoteTransfer->getTotalUsedCashbackBalanceAmount() > 0;
    }

    /**
     * @return int
     */
    protected function getPaymentOptionId(): int
    {
        return $this->config->getOptionCashback();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    protected function getAmount(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer->getTotalUsedCashbackBalanceAmount();
    }
}
