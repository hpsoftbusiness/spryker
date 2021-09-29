<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Pyz\Shared\Discount\DiscountConstants;

class DiscountTotalWithoutShoppingPointsCalculator implements MyWorldPaymentQuoteCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $discountTotal = $calculableObjectTransfer->getTotals()->getDiscountTotal();

        foreach ($calculableObjectTransfer->getCartRuleDiscounts() as $cartRuleDiscount) {
            if ($cartRuleDiscount->getDiscountType() === DiscountConstants::TYPE_INTERNAL_DISCOUNT) {
                $discountTotal -= $cartRuleDiscount->getAmount();
            }
        }

        $calculableObjectTransfer->getTotals()->setDiscountTotalWithoutShoppingPoints($discountTotal);

        return $calculableObjectTransfer;
    }
}
