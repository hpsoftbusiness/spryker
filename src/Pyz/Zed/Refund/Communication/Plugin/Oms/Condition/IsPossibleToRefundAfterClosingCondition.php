<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Oms\Condition;

use DateTime;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class IsPossibleToRefundAfterClosingCondition extends AbstractPlugin implements ConditionInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $isAuthorizedToRefund = $this->getFactory()->createIsAuthorizedToRefundCondition()->check($orderItem);
        $isNotRefunded = !$this->getFactory()->createIsRefundedCondition()->check($orderItem);
        /** @var \Orm\Zed\Oms\Persistence\Base\SpyOmsOrderItemStateHistory $lastState */
        $lastState = $orderItem->getStateHistories()->getFirst();
        $isClosedLessThanTwoYearsAgo = $lastState->getCreatedAt() > (new DateTime())->modify('2 years ago');

        return $isAuthorizedToRefund && $isNotRefunded && $isClosedLessThanTwoYearsAgo;
    }
}
