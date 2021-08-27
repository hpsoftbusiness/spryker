<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Pyz\Shared\Shipment\ShipmentConfig;

class GrandTotalWithDealsCalculator implements MyWorldPaymentQuoteCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $grandTotal = $calculableObjectTransfer->getTotals()->getSubtotalWithDeals();

        /** @var \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer */
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                $grandTotal += $expenseTransfer->getSumPriceToPayAggregation();
            }
        }

        $calculableObjectTransfer->getTotals()->setGrandTotalWithDeals($grandTotal);

        return $calculableObjectTransfer;
    }
}
