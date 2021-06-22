<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Oms\Condition;

use Orm\Zed\Refund\Persistence\Base\PyzSalesOrderItemRefund;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class IsPendingCondition extends AbstractPlugin implements ConditionInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $itemRefundStates = array_map(
            function (PyzSalesOrderItemRefund $pyzSalesOrderItemRefund): string {
                return $pyzSalesOrderItemRefund->getStatus();
            },
            $orderItem->getPyzSalesOrderItemRefunds()->getData()
        );

        return in_array(RefundConfig::PAYMENT_REFUND_STATUS_PENDING, $itemRefundStates)
            && !in_array(RefundConfig::PAYMENT_REFUND_STATUS_FAILED, $itemRefundStates);
    }
}
