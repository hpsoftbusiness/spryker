<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface;

class TurnoverCalculator implements MyWorldPaymentQuoteCalculatorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface
     */
    protected $itemTransferDealsChecker;

    /**
     * @param \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface $itemTransferDealsChecker
     */
    public function __construct(ItemTransferDealsCheckerInterface $itemTransferDealsChecker)
    {
        $this->itemTransferDealsChecker = $itemTransferDealsChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $items = $calculableObjectTransfer->getItems();
        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        foreach ($items as $item) {
            $turnoverAmount = $item->getSumPriceToPayAggregation();

            if ($this->itemTransferDealsChecker->hasShoppingPointsDeals($item)) {
                $shoppingPointsAmount = $item->getUnitPrice() - $item->getShoppingPointsDeal()->getPrice();
                $turnoverAmount += $shoppingPointsAmount * $item->getQuantity();
            }

            $item->setTurnoverAmount($turnoverAmount);
        }

        return $calculableObjectTransfer;
    }
}
