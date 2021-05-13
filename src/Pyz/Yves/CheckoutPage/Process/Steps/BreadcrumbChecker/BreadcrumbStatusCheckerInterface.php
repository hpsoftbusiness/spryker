<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker;

use Generated\Shared\Transfer\QuoteTransfer;

interface BreadcrumbStatusCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isEnabled(QuoteTransfer $quoteTransfer): bool;
}
