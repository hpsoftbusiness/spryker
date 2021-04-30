<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class IsMyWorldPaymentInitiated extends AbstractPlugin implements ConditionInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $spyOrderEntity = $orderItem->getOrder();

        return $this->assertOrderBenefitDealsApplied($spyOrderEntity)
            || $this->assertMyWorldPaymentMethodUsed($spyOrderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return bool
     */
    private function assertOrderBenefitDealsApplied(SpySalesOrder $order): bool
    {
        $orderBenefitDeals = $order->getPyzSalesOrderBenefitDeals();
        if ($orderBenefitDeals->count() === 0) {
            return false;
        }

        $orderBenefitDeals = $orderBenefitDeals[0];

        return (float)$orderBenefitDeals->getTotalBenefitVouchersAmount() > 0
            || (float)$orderBenefitDeals->getTotalShoppingPointsAmount() > 0;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return bool
     */
    private function assertMyWorldPaymentMethodUsed(SpySalesOrder $order): bool
    {
        $orderPayments = $order->getOrdersJoinSalesPaymentMethodType();
        if ($orderPayments->count() === 0) {
            return false;
        }

        foreach ($orderPayments as $orderPayment) {
            $paymentMethodType = $orderPayment->getSalesPaymentMethodType();
            if ($paymentMethodType
                 && $paymentMethodType->getPaymentProvider() === MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD
            ) {
                return true;
            }
        }

        return false;
    }
}
