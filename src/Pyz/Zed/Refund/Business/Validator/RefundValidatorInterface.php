<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Validator;

interface RefundValidatorInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function validate(array $orderItems, int $idSalesOrder): void;
}
