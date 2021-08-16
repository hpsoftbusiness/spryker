<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

interface DefaultShipmentMethodQuoteExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function expand(QuoteTransfer $quoteTransfer): void;
}
