<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence;

use Orm\Zed\Refund\Persistence\PyzSalesExpenseRefundQuery;
use Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefundQuery;
use Pyz\Zed\Refund\Persistence\Propel\Mapper\ExpenseRefundMapper;
use Pyz\Zed\Refund\Persistence\Propel\Mapper\ItemRefundMapper;
use Spryker\Zed\Refund\Persistence\RefundPersistenceFactory as SprykerRefundPersistenceFactory;

class RefundPersistenceFactory extends SprykerRefundPersistenceFactory
{
    /**
     * @return \Pyz\Zed\Refund\Persistence\Propel\Mapper\ItemRefundMapper
     */
    public function createItemRefundMapper(): ItemRefundMapper
    {
        return new ItemRefundMapper();
    }

    /**
     * @return \Pyz\Zed\Refund\Persistence\Propel\Mapper\ExpenseRefundMapper
     */
    public function createExpenseRefundMapper(): ExpenseRefundMapper
    {
        return new ExpenseRefundMapper();
    }

    /**
     * @return \Orm\Zed\Refund\Persistence\PyzSalesExpenseRefundQuery
     */
    public function createPyzSalesExpenseRefundQuery(): PyzSalesExpenseRefundQuery
    {
        return PyzSalesExpenseRefundQuery::create();
    }

    /**
     * @return \Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefundQuery
     */
    public function createPyzSalesOrderItemRefundQuery(): PyzSalesOrderItemRefundQuery
    {
        return PyzSalesOrderItemRefundQuery::create();
    }
}
