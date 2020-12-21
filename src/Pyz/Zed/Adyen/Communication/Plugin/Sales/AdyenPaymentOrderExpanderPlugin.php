<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;

/**
 * @method \Pyz\Zed\Adyen\Business\AdyenFacadeInterface getFacade()
 * @method \Pyz\Zed\Adyen\Communication\AdyenCommunicationFactory getFactory()
 * @method \Pyz\Zed\Adyen\AdyenConfig getConfig()
 */
class AdyenPaymentOrderExpanderPlugin extends AbstractPlugin implements OrderExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands order with payment adyen transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->expandOrderWithAdyenPayment($orderTransfer);
    }
}
