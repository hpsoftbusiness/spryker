<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Pyz\Zed\Refund\Business\RefundFacadeInterface;
use Spryker\Zed\Calculation\Business\Model\Calculator\CanceledTotalCalculator as SprykerCanceledTotalCalculator;

class CanceledTotalCalculator extends SprykerCanceledTotalCalculator
{
    /**
     * @var \Pyz\Zed\Refund\Business\RefundFacadeInterface
     */
    protected $refundFacade;

    /**
     * @param \Pyz\Zed\Refund\Business\RefundFacadeInterface $refundFacade
     */
    public function __construct(RefundFacadeInterface $refundFacade)
    {
        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateItemTotalCanceledAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $idSalesOrder = $this->getIdSalesOrder($calculableObjectTransfer);
        if ($idSalesOrder === null) {
            return 0;
        }

        $canceledTotal = 0;
        $itemRefunds = $this->refundFacade->findOrderItemRefundsByIdSalesOrder($idSalesOrder);
        foreach ($itemRefunds as $itemRefund) {
            $canceledTotal += $itemRefund->getAmount();
        }

        return $canceledTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateOrderExpenseCanceledAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $idSalesOrder = $this->getIdSalesOrder($calculableObjectTransfer);
        if ($idSalesOrder === null) {
            return 0;
        }

        $canceledTotal = 0;
        $expenseRefunds = $this->refundFacade->findOrderExpenseRefundsByIdSalesOrder($idSalesOrder);
        foreach ($expenseRefunds as $expenseRefund) {
            $canceledTotal += $expenseRefund->getAmount();
        }

        return $canceledTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int|null
     */
    protected function getIdSalesOrder(CalculableObjectTransfer $calculableObjectTransfer): ?int
    {
        foreach ($calculableObjectTransfer->getItems() as $item) {
            if ($item->getFkSalesOrder() === null) {
                continue;
            }

            return $item->getFkSalesOrder();
        }

        return null;
    }
}
