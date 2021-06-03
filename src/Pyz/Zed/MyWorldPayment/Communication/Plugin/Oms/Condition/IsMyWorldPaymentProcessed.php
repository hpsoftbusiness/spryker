<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Oms\Condition;

use Orm\Zed\MyWorldPayment\Persistence\Map\PyzPaymentMyWorldTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Pyz\Zed\MyWorldPayment\Communication\MyWorldPaymentCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class IsMyWorldPaymentProcessed extends AbstractPlugin implements ConditionInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $myWorldPayments = $orderItem->getOrder()->getPyzPaymentMyWorlds();

        if ($myWorldPayments->count() === 0) {
            return false;
        }

        $myWorldPayment = $myWorldPayments[0];

        return $myWorldPayment->getStatus() === PyzPaymentMyWorldTableMap::COL_STATUS_CHARGED;
    }
}
