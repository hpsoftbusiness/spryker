<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
