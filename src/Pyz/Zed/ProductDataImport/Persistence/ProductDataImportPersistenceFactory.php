<?php

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
     * @return SpyProductDataImportQuery
     */
    public function createSpyProductImportDataQuery(): SpyProductDataImportQuery
    {
        return SpyProductDataImportQuery::create();
    }
}
