<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Communication\Plugin\ItemHydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer;
use Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemBenefitDealHydratorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface getFacade()
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 */
class BenefitVoucherItemHydratorPlugin extends AbstractPlugin implements ItemBenefitDealHydratorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer
     *
     * @return void
     */
    public function hydrateItem(ItemTransfer $itemTransfer, PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer): void
    {
        $itemTransfer->setOriginUnitGrossPrice($benefitDealEntityTransfer->getOriginUnitGrossPrice());
        $itemTransfer->setTotalUsedBenefitVouchersAmount($benefitDealEntityTransfer->getBenefitVoucherAmount());
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getConfig()->getBenefitVoucherPaymentName();
    }
}
