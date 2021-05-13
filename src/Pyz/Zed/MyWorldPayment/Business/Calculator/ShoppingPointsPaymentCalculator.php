<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Service\Customer\CustomerServiceInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ShoppingPointsPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    /**
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

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

        $availableShoppingPointsAmount = $this->customerService->getCustomerShoppingPointsBalanceAmount($customerTransfer);
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getUseShoppingPoints() || $availableShoppingPointsAmount === (float)0) {
                $itemTransfer->setTotalUsedShoppingPointsAmount(0);

                $itemTransfer->setUnitGrossPrice($itemTransfer->getOriginUnitGrossPrice());
                $itemTransfer->setSumGrossPrice($itemTransfer->getOriginUnitGrossPrice() * $itemTransfer->getQuantity());

                continue;
            }

            $this->calculateItemUsedShoppingPoints($itemTransfer, $availableShoppingPointsAmount);
            $availableShoppingPointsAmount -= $itemTransfer->getTotalUsedShoppingPointsAmount();
        }

        $calculableObjectTransfer->setTotalUsedShoppingPointsAmount(
            $this->getCommonUsedShoppingPoints($calculableObjectTransfer)
        );

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return float
     */
    protected function getCommonUsedShoppingPoints(CalculableObjectTransfer $calculableObjectTransfer): float
    {
        return array_reduce(
            $calculableObjectTransfer->getItems()->getArrayCopy(),
            function (float $carry, ItemTransfer $itemTransfer) {
                $carry += $itemTransfer->getTotalUsedShoppingPointsAmount();

                return $carry;
            },
            0
        );
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
