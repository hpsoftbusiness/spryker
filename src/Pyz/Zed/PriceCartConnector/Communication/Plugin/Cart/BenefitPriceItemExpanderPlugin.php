<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceCartConnector\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface getFacade()
 */
class BenefitPriceItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer|void
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()->expandItemsWithBenefitPrices($cartChangeTransfer);
    }
}
