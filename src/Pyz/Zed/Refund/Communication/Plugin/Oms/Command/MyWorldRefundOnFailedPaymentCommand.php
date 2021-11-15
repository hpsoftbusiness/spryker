<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class MyWorldRefundOnFailedPaymentCommand extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $refundTransfer = $this
            ->getFacade()
            ->calculateRefundWithoutExternalPayment($orderItems, $orderEntity);

        if ($refundTransfer->getAmount() > 0) {
            $this
                ->getFacade()
                ->saveRefund($refundTransfer);

            $this
                ->getFacade()
                ->processRefund($orderItems, $orderEntity);
        }

        $this->recalculateOrder($orderEntity);

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    protected function recalculateOrder(SpySalesOrder $orderEntity): void
    {
        $orderTransfer = $this->getFactory()->getSalesFacade()->findOrderByIdSalesOrder($orderEntity->getIdSalesOrder());
        $orderTransfer = $this->getFactory()->getCalculationFacade()->recalculateOrder($orderTransfer);
        $this->getFactory()->getSalesFacade()->updateOrder($orderTransfer, $orderEntity->getIdSalesOrder());
    }
}
