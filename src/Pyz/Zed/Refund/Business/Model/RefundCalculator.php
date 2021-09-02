<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Zed\Refund\Business\Calculator\Payment\RefundablePaymentCalculatorInterface;
use Spryker\Zed\Refund\Business\Model\RefundCalculator as SprykerRefundCalculator;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;

class RefundCalculator extends SprykerRefundCalculator
{
    /**
     * @var \Pyz\Zed\Refund\Business\Calculator\Payment\RefundablePaymentCalculatorInterface
     */
    private $refundablePaymentCalculator;

    /**
     * @param \Spryker\Zed\Refund\Dependency\Plugin\RefundCalculatorPluginInterface[] $refundCalculatorPlugins
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface $salesFacade
     * @param \Pyz\Zed\Refund\Business\Calculator\Payment\RefundablePaymentCalculatorInterface $refundablePaymentCalculator
     */
    public function __construct(
        array $refundCalculatorPlugins,
        RefundToSalesInterface $salesFacade,
        RefundablePaymentCalculatorInterface $refundablePaymentCalculator
    ) {
        parent::__construct($refundCalculatorPlugins, $salesFacade);

        $this->refundablePaymentCalculator = $refundablePaymentCalculator;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
    {
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);
        $orderTransfer = $this->refundablePaymentCalculator->calculateOrderPaymentsRefundableAmount($orderTransfer);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(0);
        $refundTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());

        foreach ($this->refundCalculatorPlugins as $refundCalculatorPlugin) {
            $refundTransfer = $refundCalculatorPlugin->calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);
        }

        return $refundTransfer;
    }
}
