<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpandBeforeCreatePluginInterface;

/**
 * @method \Pyz\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 */
class DefaultShipmentMethodQuoteExpandBeforeCreatePlugin extends AbstractPlugin implements QuoteExpandBeforeCreatePluginInterface
{
    /**
     * Specification:
     * - Expands quote transfer before quote creation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->getFacade()->expandQuoteWithDefaultShipmentMethod($quoteTransfer);

        return $quoteTransfer;
    }
}
