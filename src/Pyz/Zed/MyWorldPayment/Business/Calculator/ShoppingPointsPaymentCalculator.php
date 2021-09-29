<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface;

class ShoppingPointsPaymentCalculator implements
    MyWorldPaymentQuoteCalculatorInterface,
    MyWorldPaymentOrderCalculatorInterface
{
    /**
     * @var \Pyz\Service\Customer\CustomerServiceInterface
     */
    private $customerService;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface
     */
    protected $itemTransferDealsChecker;

    /**
     * @param \Pyz\Service\Customer\CustomerServiceInterface $customerService
     * @param \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface $itemTransferDealsChecker
     */
    public function __construct(
        CustomerServiceInterface $customerService,
        ItemTransferDealsCheckerInterface $itemTransferDealsChecker
    ) {
        $this->customerService = $customerService;
        $this->itemTransferDealsChecker = $itemTransferDealsChecker;
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
            if (!$itemTransfer->getUseShoppingPoints()
                || $availableShoppingPointsAmount === (float)0
                || !$this->itemTransferDealsChecker->hasShoppingPointsDeals($itemTransfer)
            ) {
                $itemTransfer->setTotalUsedShoppingPointsAmount(0);
                $itemTransfer->setUseShoppingPoints(false);

                continue;
            }

            $this->calculateItemUsedShoppingPoints($itemTransfer, $availableShoppingPointsAmount);
            $this->setUnitBenefitPrice($itemTransfer);
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
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getUseShoppingPoints() || !$this->itemTransferDealsChecker->hasShoppingPointsDeals($itemTransfer)) {
                continue;
            }

            $shoppingPointsDealTransfer = $itemTransfer->getShoppingPointsDeal();
            $totalItemUsedShoppingPointAmount = $itemTransfer->getQuantity() * $shoppingPointsDealTransfer->getShoppingPointsQuantity();
            $itemTransfer->setTotalUsedShoppingPointsAmount($totalItemUsedShoppingPointAmount);
            $this->setUnitBenefitPrice($itemTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function setUnitBenefitPrice(ItemTransfer $itemTransfer): void
    {
        $unitBenefitPrice = $itemTransfer->getShoppingPointsDeal()->getPrice();
        $itemTransfer->setUnitBenefitPrice($unitBenefitPrice);
        $itemTransfer->setSumBenefitPrice($unitBenefitPrice * $itemTransfer->getQuantity());
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
        $totalShoppingPointsForItem = $itemTransfer->getQuantity() * $shoppingPointsDealTransfer->getShoppingPointsQuantity();
        if ($totalShoppingPointsForItem > $availablePoints) {
            return;
        }

        $itemTransfer->setTotalUsedShoppingPointsAmount($totalShoppingPointsForItem);
    }
}
