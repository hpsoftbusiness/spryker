<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber\SegmentNumberNewCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber\SegmentNumberOneSenseCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber\SegmentNumberZeroMarginCalculator;

class SegmentNumberCalculator implements
    MyWorldPaymentQuoteCalculatorInterface,
    MyWorldPaymentOrderCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($this->getSegmentNumberCalculators() as $calculator) {
            $calculableObjectTransfer = $calculator->recalculateQuote($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($this->getSegmentNumberCalculators() as $calculator) {
            $calculableObjectTransfer = $calculator->recalculateOrder($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface[]|\Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentOrderCalculatorInterface[]
     */
    protected function getSegmentNumberCalculators(): array
    {
        return [
            new SegmentNumberOneSenseCalculator(),
            new SegmentNumberZeroMarginCalculator(),
            new SegmentNumberNewCalculator(),
        ];
    }
}
