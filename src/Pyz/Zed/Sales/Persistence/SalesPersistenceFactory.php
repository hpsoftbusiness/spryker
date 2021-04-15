<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Pyz\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapper;
use Pyz\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper as SprykerSalesOrderMapper;
use Spryker\Zed\Sales\Persistence\SalesPersistenceFactory as SprykerSalesPersistenceFactory;

/**
 * @method \Pyz\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class SalesPersistenceFactory extends SprykerSalesPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper
     */
    public function createSalesOrderMapper(): SprykerSalesOrderMapper
    {
        return new SalesOrderMapper();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface
     */
    public function createSalesOrderItemMapper(): SalesOrderItemMapperInterface
    {
        return new SalesOrderItemMapper();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function createOmsOrderItemStateQuery(): SpyOmsOrderItemStateQuery
    {
        return SpyOmsOrderItemStateQuery::create();
    }
}
