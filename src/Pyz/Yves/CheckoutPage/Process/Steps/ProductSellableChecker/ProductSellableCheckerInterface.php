<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker;

use Generated\Shared\Transfer\QuoteTransfer;

interface ProductSellableCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $isQuoteValid
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer, bool $isQuoteValid): bool;
}
