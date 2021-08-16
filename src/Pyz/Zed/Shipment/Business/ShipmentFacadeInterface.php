<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface as SprykerShipmentFacadeInterface;

interface ShipmentFacadeInterface extends SprykerShipmentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function expandQuoteWithDefaultShipmentMethod(QuoteTransfer $quoteTransfer): void;
}
