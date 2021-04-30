<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteEqualizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function equalize(
        QuoteTransfer $resultQuoteTransfer,
        QuoteTransfer $sourceQuoteTransfer
    ): void;
}
