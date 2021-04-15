<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;

/**
 * @method \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface getFacade()
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 */
class BenefitDealOrderExpanderPlugin extends AbstractPlugin implements OrderExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrate(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFacade()->hydrateOrderWithBenefitDeal($orderTransfer);
    }
}
