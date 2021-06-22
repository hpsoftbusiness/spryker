<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector\Business;

use Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorFacade as SprykerProductCartConnectorFacade;

/**
 * @method \Pyz\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getFactory()
 */
class ProductCartConnectorFacade extends SprykerProductCartConnectorFacade implements ProductCartConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function expandItemsWithBenefitDeals(iterable $itemTransfers): void
    {
        $this->getFactory()->createBenefitDealsExpander()->expandItems($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function expandItemsWithBenefitDealsChargeAmount(iterable $itemTransfers): void
    {
        $this->getFactory()->createBenefitDealsChargeAmountExpander()->expandItems($itemTransfers);
    }
}
