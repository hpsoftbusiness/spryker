<?php

namespace Pyz\Zed\ProductManagement\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Pyz\Zed\ProductManagement\Communication\Controller\IndexController;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductManagement\Communication\Table\ProductTable as SprykerProductTable;

class ProductTable extends SprykerProductTable
{
    /**
     * @param SpyProductAbstract $item
     * @return array
     */
    protected function createActionColumn(SpyProductAbstract $item): array
    {
        $urls = parent::createActionColumn($item);

        $removeUrl = Url::generate(
            IndexController::ROUTE_SOFT_REMOVE,
            [
                IndexController::ID_PRODUCT_ABSTRACT => $item->getIdProductAbstract(),
            ]
        );
        $urls[] = $this->generateButton(
            '#removeModal',
            'Remove',
            [self::BUTTON_CLASS => 'btn-remove'],
            ['data-toggle' => 'modal', 'data-url' => $removeUrl]
        );

        return $urls;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this
            ->productQueryQueryContainer
            ->queryProductAbstract()
            ->innerJoinSpyTaxSet()
            ->filterByIsRemoved(false)
            ->useSpyProductAbstractLocalizedAttributesQuery()
            ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(SpyTaxSetTableMap::COL_NAME, static::COL_TAX_SET);

        $query = $this->expandPropelQuery($query);

        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productAbstractEntity) {
            $productAbstractCollection[] = $this->generateItem($productAbstractEntity);
        }

        return $productAbstractCollection;
    }
}
