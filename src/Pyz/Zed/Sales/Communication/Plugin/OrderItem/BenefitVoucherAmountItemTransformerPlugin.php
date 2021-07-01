<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Plugin\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\Sales\Dependency\Plugin\OrderItemTransformerPluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Pyz\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Pyz\Zed\Sales\SalesConfig getConfig()
 */
class BenefitVoucherAmountItemTransformerPlugin extends AbstractPlugin implements OrderItemTransformerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $originalItemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $transformedItemTransfer
     *
     * @return void
     */
    public function transform(ItemTransfer $originalItemTransfer, ItemTransfer $transformedItemTransfer): void
    {
        if (!$this->assertBenefitVoucherDealApplied($originalItemTransfer)) {
            return;
        }

        $maxApplicableBenefitVoucherAmount = $originalItemTransfer->getBenefitVoucherDealData()->getAmount();
        $totalItemApplicableAmount = $originalItemTransfer->getTotalUsedBenefitVouchersAmount();
        $unitApplicableAmount = min($maxApplicableBenefitVoucherAmount, $totalItemApplicableAmount);
        $transformedItemTransfer->setTotalUsedBenefitVouchersAmount($unitApplicableAmount);
        $originalItemTransfer->setTotalUsedBenefitVouchersAmount($totalItemApplicableAmount - $unitApplicableAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    private function assertBenefitVoucherDealApplied(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireUseBenefitVoucher();
            $itemTransfer->requireTotalUsedBenefitVouchersAmount();

            return $itemTransfer->getUseBenefitVoucher();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }
}
