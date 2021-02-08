<?php

namespace Pyz\Zed\ProductDataImport\Persistence;

use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductDataImportQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @return SpyProductDataImportQuery
     */
    public function queryProductImports(): SpyProductDataImportQuery;

    /**
     * @param int $idProductDataImport
     * @return SpyProductDataImportQuery
     */
    public function queryProductDataImportById(int $idProductDataImport): SpyProductDataImportQuery;
}
