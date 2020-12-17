<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
