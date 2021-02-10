<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Persistence;

use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class ProductDataImportPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery
     */
    public function createSpyProductImportDataQuery(): SpyProductDataImportQuery
    {
        return SpyProductDataImportQuery::create();
    }
}
