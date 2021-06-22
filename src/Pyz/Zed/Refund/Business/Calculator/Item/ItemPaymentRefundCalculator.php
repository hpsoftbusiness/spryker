<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Calculator\Item;

use Generated\Shared\Transfer\ItemTransfer;

class ItemPaymentRefundCalculator implements ItemPaymentRefundCalculatorInterface
{
    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\ItemRefundCalculatorPluginInterface[]
     */
    private $calculatorPlugins;

    /**
     * @param \Pyz\Zed\Refund\Dependency\Plugin\ItemRefundCalculatorPluginInterface[] $itemCalculatorPlugins
     */
    public function __construct(array $itemCalculatorPlugins)
    {
        $this->calculatorPlugins = $itemCalculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function calculateItemRefunds(ItemTransfer $itemTransfer, array $paymentTransfers): ItemTransfer
    {
        $itemCanceledAmount = $itemTransfer->getCanceledAmount();
        foreach ($this->calculatorPlugins as $calculatorPlugin) {
            if ($itemTransfer->getCanceledAmount() === 0) {
                break;
            }

            $paymentTransfer = $calculatorPlugin->findApplicablePayment($paymentTransfers);
            if (!$paymentTransfer || $paymentTransfer->getRefundableAmount() === 0) {
                continue;
            }

            $itemRefundTransfer = $calculatorPlugin->calculateItemRefund($itemTransfer, $paymentTransfer);
            if (!$itemRefundTransfer) {
                continue;
            }

            $itemTransfer->addRefund($itemRefundTransfer);
            $itemTransfer->setCanceledAmount($itemTransfer->getCanceledAmount() - $itemRefundTransfer->getAmount());
        }

        $itemTransfer->setCanceledAmount($itemCanceledAmount);

        return $itemTransfer;
    }
}
