<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Communication;

use Codeception\Module;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\DataBuilder\RefundBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RefundTransfer;

class DataHelper extends Module
{
    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function buildItemTransfer(array $overrideData): ItemTransfer
    {
        return (new ItemBuilder($overrideData))->build();
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function buildPaymentTransfer(array $overrideData): PaymentTransfer
    {
        return (new PaymentBuilder($overrideData))->build();
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function buildExpenseTransfer(array $overrideData): ExpenseTransfer
    {
        return (new ExpenseBuilder($overrideData))->build();
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function buildRefundTransfer(array $overrideData): RefundTransfer
    {
        return (new RefundBuilder($overrideData))->build();
    }
}
