<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Persistence;

use Pyz\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper;
use Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper as SprykerProductMapper;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorPersistenceFactory as SprykerSalesProductConnectorPersistenceFactory;

class SalesProductConnectorPersistenceFactory extends SprykerSalesProductConnectorPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper\ProductMapper
     */
    public function createProductMapper(): SprykerProductMapper
    {
        return new ProductMapper();
    }
}
