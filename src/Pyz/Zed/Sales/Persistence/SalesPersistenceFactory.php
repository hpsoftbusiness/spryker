<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence;

use Pyz\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper;
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
}
