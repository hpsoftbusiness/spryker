<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ShoppingPointsPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $customerTransfer = $calculableObjectTransfer->getOriginalQuote()->getCustomer();
        if (!$customerTransfer) {
            return $calculableObjectTransfer;
        }

        $availableShoppingPointsAmount = $customerTransfer->getCustomerBalance()->getAvailableShoppingPointAmount()->toFloat();
        $totalShoppingPointsAmountSpent = 0;
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getUseShoppingPoints() || $availableShoppingPointsAmount === 0) {
                continue;
            }

            $this->calculateItemUsedShoppingPoints($itemTransfer, $availableShoppingPointsAmount);
            $totalItemUsedShoppingPoints = $itemTransfer->getTotalUsedShoppingPointsAmount();
            $totalShoppingPointsAmountSpent += $totalItemUsedShoppingPoints;
            $availableShoppingPointsAmount -= $totalItemUsedShoppingPoints;
        }

        $calculableObjectTransfer->setTotalUsedShoppingPointsAmount($totalShoppingPointsAmountSpent);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param float $availablePoints
     *
     * @return void
     */
    private function calculateItemUsedShoppingPoints(ItemTransfer $itemTransfer, float $availablePoints): void
    {
        $shoppingPointsDealTransfer = $itemTransfer->getShoppingPointsDeal();
        if (!$shoppingPointsDealTransfer || !$this->assertShoppingPointsDealTransfer($shoppingPointsDealTransfer)) {
            return;
        }

        $totalShoppingPointsForItem = $itemTransfer->getQuantity() * $shoppingPointsDealTransfer->getShoppingPointsQuantity();
        if ($totalShoppingPointsForItem > $availablePoints) {
            return;
        }

        $itemTransfer->setTotalUsedShoppingPointsAmount($totalShoppingPointsForItem);
        $itemTransfer->setUnitGrossPrice($shoppingPointsDealTransfer->getPrice());
        $itemTransfer->setSumGrossPrice($shoppingPointsDealTransfer->getPrice() * $itemTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingPointsDealTransfer $shoppingPointsDealTransfer
     *
     * @return bool
     */
    private function assertShoppingPointsDealTransfer(ShoppingPointsDealTransfer $shoppingPointsDealTransfer): bool
    {
        try {
            $shoppingPointsDealTransfer->requireIsActive();
            $shoppingPointsDealTransfer->requireShoppingPointsQuantity();
            $shoppingPointsDealTransfer->requirePrice();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }

        return $shoppingPointsDealTransfer->getShoppingPointsQuantity() > 0 && $shoppingPointsDealTransfer->getPrice() > 0;
    }
}
