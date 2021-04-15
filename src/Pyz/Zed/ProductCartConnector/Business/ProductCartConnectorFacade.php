<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade as SprykerProductCartConnectorFacade;

/**
 * @method \Pyz\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getFactory()
 */
class ProductCartConnectorFacade extends SprykerProductCartConnectorFacade implements ProductCartConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemsWithBenefitDeals(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()->createBenefitDealsExpander()->expandItems($cartChangeTransfer);
    }
}
