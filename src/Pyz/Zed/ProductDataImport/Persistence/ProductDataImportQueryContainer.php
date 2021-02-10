<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Persistence;

use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportPersistenceFactory getFactory()
 */
class ProductDataImportQueryContainer extends AbstractQueryContainer implements ProductDataImportQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery
     */
    public function queryProductImports(): SpyProductDataImportQuery
    {
        return $this->getFactory()->createSpyProductImportDataQuery();
    }

    /**
     * @param int $idProductDataImport
     *
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery
     */
    public function queryProductDataImportById(int $idProductDataImport): SpyProductDataImportQuery
    {
        return $this->getFactory()
            ->createSpyProductImportDataQuery()
            ->filterByIdProductDataImport($idProductDataImport);
    }
}
