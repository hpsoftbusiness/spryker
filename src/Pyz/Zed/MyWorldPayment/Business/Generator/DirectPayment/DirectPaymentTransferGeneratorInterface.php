<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DirectPaymentTransferGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer
     */
    public function generateMwsDirectPaymentOptionTransfer(
        QuoteTransfer $quoteTransfer
    ): MwsDirectPaymentOptionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isPaymentUsed(QuoteTransfer $quoteTransfer): bool;
}
