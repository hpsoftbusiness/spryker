<?php

namespace Pyz\Zed\ProductDataImport\Persistence;

use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportPersistenceFactory getFactory()
 */
class ProductDataImportQueryContainer extends AbstractQueryContainer implements ProductDataImportQueryContainerInterface
{
    /**
     * @return SpyProductDataImportQuery
     */
    public function queryProductImports(): SpyProductDataImportQuery
    {
        return $this->getFactory()->createSpyProductImportDataQuery();
    }

    /**
     * @param int $idProductDataImport
     * @return SpyProductDataImportQuery
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function queryProductDataImportById(int $idProductDataImport): SpyProductDataImportQuery
    {
        return $this->getFactory()
            ->createSpyProductImportDataQuery()
            ->filterByIdProductDataImport($idProductDataImport);
    }
}
