<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Communication\Plugin\Refund;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\Refund\Calculator\ExpenseRefundCalculatorTrait;
use Pyz\Shared\Refund\Calculator\ItemRefundCalculatorTrait;
use Pyz\Zed\Refund\Dependency\Plugin\ExpenseRefundCalculatorPluginInterface;
use Pyz\Zed\Refund\Dependency\Plugin\ItemRefundCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEco\Shared\Adyen\AdyenConfig;

/**
 * @method \Pyz\Zed\Adyen\Communication\AdyenCommunicationFactory getFactory()
 * @method \Pyz\Zed\Adyen\Business\AdyenFacadeInterface getFacade()
 * @method \Pyz\Zed\Adyen\AdyenConfig getConfig()
 */
class AdyenRefundCalculatorPlugin extends AbstractPlugin implements
    ItemRefundCalculatorPluginInterface,
    ExpenseRefundCalculatorPluginInterface
{
    use ItemRefundCalculatorTrait;
    use ExpenseRefundCalculatorTrait;

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer|null
     */
    public function calculateExpenseRefund(ExpenseTransfer $expenseTransfer, PaymentTransfer $paymentTransfer): ?ExpenseRefundTransfer
    {
        $refundAmount = $this->calculateRefundAmount($expenseTransfer->getCanceledAmount(), $paymentTransfer);

        return $this->createExpenseRefundTransfer(
            $expenseTransfer->getIdSalesExpense(),
            $paymentTransfer->getIdSalesPayment(),
            $refundAmount
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer|null
     */
    public function calculateItemRefund(ItemTransfer $itemTransfer, PaymentTransfer $paymentTransfer): ?ItemRefundTransfer
    {
        $refundAmount = $this->calculateRefundAmount($itemTransfer->getCanceledAmount(), $paymentTransfer);

        return $this->createItemRefundTransfer(
            $itemTransfer->getIdSalesOrderItem(),
            $paymentTransfer->getIdSalesPayment(),
            $refundAmount
        );
    }

    /**
     * @param int $canceledAmount
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return int
     */
    private function calculateRefundAmount(int $canceledAmount, PaymentTransfer $paymentTransfer): int
    {
        $refundAmount = min($canceledAmount, $paymentTransfer->getRefundableAmount());
        $paymentTransfer->setRefundableAmount($paymentTransfer->getRefundableAmount() - $refundAmount);

        return $refundAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    public function findApplicablePayment(array $paymentTransfers): ?PaymentTransfer
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === AdyenConfig::PROVIDER_NAME) {
                return $paymentTransfer;
            }
        }

        return null;
    }
}
