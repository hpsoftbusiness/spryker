<?php

namespace Pyz\Zed\ProductDataImport\Communication\Table;

use Orm\Zed\ProductDataImport\Persistence\Map\SpyProductDataImportTableMap;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
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
    public const COL_IMPORT_RESULT = 'result';

    public const ACTIONS = 'Actions';

    public const TIME_FORMAT = 'Y-m-d H:i:s';

    /** @var ProductDataImportQueryContainerInterface */
    protected $productImportQueryContainer;

    /**
     * ProductDataImportTable constructor.
     * @param ProductDataImportQueryContainerInterface $productImportQueryContainer
     */
    public function __construct(ProductDataImportQueryContainerInterface $productImportQueryContainer)
    {
        $this->productImportQueryContainer = $productImportQueryContainer;
    }

    /**
     * @param TableConfiguration $config
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader(
            [
                static::COL_ID => '#',
                static::COL_CREATED_AT => 'Create At',
                static::COL_UPDATED_AT => 'Updated At',
                static::COL_FILE_PATH => 'File path',
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
                SpyProductDataImportTableMap::COL_ID_PRODUCT_DATA_IMPORT,
            ]
        );
        $config->setSortable(
            [
                static::COL_ID => '#',
                static::COL_CREATED_AT => 'Registration Date',
                static::COL_UPDATED_AT => 'Create At',
                static::COL_STATUS => 'Status',
            ]
        );
        $config->setUrl('table');

        return $config;
    }

    /**
     * @param TableConfiguration $config
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
    protected function prepareQuery(): \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImportQuery
    {
        return $this->productImportQueryContainer->queryProductImports();
    }

    /**
     * @param ObjectCollection $productImportsCollection
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
     * @param SpyProductDataImport $productDataImport
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
     * @param SpyProductDataImport $productDataImport
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
