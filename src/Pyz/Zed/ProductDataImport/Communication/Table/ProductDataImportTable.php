<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Table;

use Orm\Zed\ProductDataImport\Persistence\Map\SpyProductDataImportTableMap;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Exception\PropelException;
use Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductDataImportTable extends AbstractTable
{
    public const COL_ID = 'id_product_data_import';
    public const COL_CREATED_AT = 'created_at';
    public const COL_STATUS = 'status';
    public const COL_UPDATED_AT = 'updated_at';
    public const COL_FILE_PATH = 'file_path';
    public const COL_STORE = 'store';
    public const COL_IMPORT_RESULT = 'result';

    public const ACTIONS = 'Actions';

    public const TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface
     */
    protected $productImportQueryContainer;

    /**
     * @param \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface $productImportQueryContainer
     */
    public function __construct(ProductDataImportQueryContainerInterface $productImportQueryContainer)
    {
        $this->productImportQueryContainer = $productImportQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader(
            [
                static::COL_ID => '#',
                static::COL_CREATED_AT => 'Create At',
                static::COL_UPDATED_AT => 'Updated At',
                static::COL_FILE_PATH => 'File path',
                static::COL_STORE => 'Store',
                static::COL_STATUS => 'Status',
                static::ACTIONS => self::ACTIONS,
            ]
        );

        $config->addRawColumn(static::COL_STATUS);
        $config->addRawColumn(static::COL_IMPORT_RESULT);
        $config->addRawColumn(self::ACTIONS);

        $config->setSearchable(
            [
                SpyProductDataImportTableMap::COL_STATUS,
                SpyProductDataImportTableMap::COL_CREATED_AT,
                SpyProductDataImportTableMap::COL_UPDATED_AT,
                SpyProductDataImportTableMap::COL_STORE,
                SpyProductDataImportTableMap::COL_ID_PRODUCT_DATA_IMPORT,
            ]
        );
        $config->setSortable(
            [
                static::COL_ID => '#',
                static::COL_CREATED_AT => 'created_at',
                static::COL_UPDATED_AT => 'updated_at',
                static::COL_STATUS => 'status',
                static::COL_STORE => 'store',
            ]
        );
        $config->setUrl('table');

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this->prepareQuery();

        $productImportsCollection = $this->runQuery($query, $config, true);

        if ($productImportsCollection->count() < 1) {
            return [];
        }

        return $this->formatProductImportCollection($productImportsCollection);
    }

    /**
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery
     */
    protected function prepareQuery(): SpyProductDataImportQuery
    {
        return $this->productImportQueryContainer->queryProductImports();
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productImportsCollection
     *
     * @return array
     */
    protected function formatProductImportCollection(ObjectCollection $productImportsCollection): array
    {
        $productImportsList = [];

        foreach ($productImportsCollection as $productImport) {
            $productImportsList[] = $this->hydrateProductDataImportListRow($productImport);
        }

        return $productImportsList;
    }

    /**
     * @param \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport $productDataImport
     *
     * @return array
     */
    protected function hydrateProductDataImportListRow(SpyProductDataImport $productDataImport): array
    {
        $productDataImportRow = $productDataImport->toArray();
        try {
            $createdAt = $productDataImport->getCreatedAt(static::TIME_FORMAT);
            $updatedAt = $productDataImport->getUpdatedAt(static::TIME_FORMAT);
        } catch (PropelException $e) {
            $createdAt = $updatedAt = null;
        }
        $productDataImportRow[static::COL_CREATED_AT] = $createdAt;
        $productDataImportRow[static::COL_UPDATED_AT] = $updatedAt;
        $productDataImportRow[static::ACTIONS] = $this->buildLinks($productDataImport);

        return $productDataImportRow;
    }

    /**
     * @param \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport $productDataImport
     *
     * @return string
     */
    private function buildLinks(SpyProductDataImport $productDataImport): string
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton(
            sprintf(
                '/product-data-import/view?id-product-data-import=%d',
                $productDataImport->getIdProductDataImport()
            ),
            'Detail'
        );

        return implode(' ', $buttons);
    }
}
