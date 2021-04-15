<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade as SprykerPriceCartConnectorFacade;

/**
 * @method \Pyz\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory getFactory()
 */
class PriceCartConnectorFacade extends SprykerPriceCartConnectorFacade implements PriceCartConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitPrices(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()->createBenefitPriceManager()->expandItemsWithBenefitPrices($cartChangeTransfer);
    }
}
