<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration;
use Pyz\Zed\ProductManagement\Communication\Controller\IndexController;
use Pyz\Zed\ProductManagement\Communication\Form\TableFilter\StatusTableFilterForm;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelperInterface;
use Spryker\Zed\ProductManagement\Communication\Table\ProductTable as SprykerProductTable;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration getConfiguration()
 */
class ProductTable extends SprykerProductTable
{
    public const COL_SELECT = 'select_all';

    public const SELECT_CHECKBOX_ID_PREFIX = 'product_abstract_select_checkbox_';

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $statusTableFilterForm;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $groupActionsForm;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelperInterface $productTypeHelper
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface $productManagementRepository
     * @param \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataExpanderPluginInterface[] $productTableDataExpanderPlugins
     * @param \Symfony\Component\Form\FormInterface $statusTableFilterForm
     * @param \Symfony\Component\Form\FormInterface $groupActionsForm
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        LocaleTransfer $localeTransfer,
        ProductTypeHelperInterface $productTypeHelper,
        ProductManagementRepositoryInterface $productManagementRepository,
        array $productTableDataExpanderPlugins,
        FormInterface $statusTableFilterForm,
        FormInterface $groupActionsForm
    ) {
        parent::__construct(
            $productQueryContainer,
            $localeTransfer,
            $productTypeHelper,
            $productManagementRepository,
            $productTableDataExpanderPlugins
        );
        $this->statusTableFilterForm = $statusTableFilterForm;
        $this->groupActionsForm = $groupActionsForm;
    }

    /**
     * @return array
     */
    public function prepareConfig()
    {
        return array_merge(parent::prepareConfig(), [
            'filters' => $this->getConfiguration()->getFilters(),
            'groupActions' => $this->getConfiguration()->getGroupActions(),
        ]);
    }

    /**
     * @return \Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration
     */
    protected function newTableConfiguration()
    {
        return new ExtendedTableConfiguration();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration $config
     * @param bool $returnRawResults
     *
     * @return array|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config, $returnRawResults = false)
    {
        if ($config->getFilters()) {
            $this->filterQuery($query, $config);
        }

        return parent::runQuery($query, $config, $returnRawResults);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration $config
     *
     * @return void
     */
    protected function filterQuery(ModelCriteria $query, ExtendedTableConfiguration $config): void
    {
        $columnsList = $this->getColumnsList($query, $config);
        /** @var array $columnsRequest */
        $columnsRequest = $this->request->query->get('columns');

        foreach ($config->getFilters() as $filterName => $filterForm) {
            $filterColumn = array_search($filterName, $columnsList);
            if (!$filterColumn) {
                continue;
            }

            $filterValue = $columnsRequest[$filterColumn]['search']['value'] ?? null;
            $methodName = 'filter' . str_replace('_', '', ucwords($filterName, '_'));
            if ($filterValue && method_exists($this, $methodName)) {
                $this->$methodName($query, $filterValue);
            }
        }
    }

    /**
     * @return string[]
     */
    protected function getTwigPaths()
    {
        return [
            __DIR__ . '/../../../Gui/Presentation/Table/',
        ];
    }

    /**
     * @param \Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate(
            '/table',
            $this->getRequest()->query->all()
        );

        $config->setUrl($url);

        $config->setHeader([
            static::COL_SELECT => $this->addCheckBox(),
            static::COL_ID_PRODUCT_ABSTRACT => 'Product ID',
            static::COL_NAME => 'Name',
            static::COL_SKU => 'Sku',
            static::COL_VARIANT_COUNT => 'Variants',
            static::COL_STATUS => 'Status',
            static::COL_PRODUCT_TYPES => 'Types',
            static::COL_STORE_RELATION => 'Stores',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_SELECT,
            static::COL_STATUS,
            static::COL_PRODUCT_TYPES,
            static::COL_STORE_RELATION,
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU,
            static::COL_NAME,
        ]);

        $config->setDefaultSortField(
            static::COL_ID_PRODUCT_ABSTRACT,
            TableConfiguration::SORT_DESC
        );

        $config->setFilters([static::COL_STATUS => $this->statusTableFilterForm->createView()]);
        $config->setGroupActions($this->groupActionsForm->createView());

        return $config;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $item
     *
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
     * @param \Pyz\Zed\Gui\Communication\Table\ExtendedTableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $query = $this
            ->productQueryQueryContainer
            ->queryProductAbstract()
            ->filterByIsRemoved(false)
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->groupByIdProductAbstract();

        $query = $this->expandPropelQuery($query);

        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productAbstractEntity) {
            $productAbstractCollection[] = $this->generateItem($productAbstractEntity);
        }

        return $productAbstractCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function generateItem(SpyProductAbstract $productAbstractEntity)
    {
        $item = [
            static::COL_SELECT => $this->addCheckBox($productAbstractEntity),
            static::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            static::COL_SKU => $productAbstractEntity->getSku(),
            static::COL_NAME => $this->resolveProductName($productAbstractEntity),
            static::COL_VARIANT_COUNT => $productAbstractEntity->getSpyProducts()->count(),
            static::COL_STATUS => $this->getAbstractProductStatusLabel($productAbstractEntity),
            static::COL_PRODUCT_TYPES => 'Product',
            static::COL_STORE_RELATION => $this->getStoreNames($productAbstractEntity->getIdProductAbstract()),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($productAbstractEntity)),
        ];

        return $this->executeItemDataExpanderPlugins($item);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     * @param mixed $value
     *
     * @return void
     */
    protected function filterStatus(SpyProductAbstractQuery $query, $value): void
    {
        switch ($value) {
            case StatusTableFilterForm::FIELD_ACTIVE:
                $query->useSpyProductQuery()
                        ->filterByIsActive(true)
                    ->endUse();

                return;
            case StatusTableFilterForm::FIELD_DEACTIVATED:
                $query->leftJoinSpyProduct(SpyProductTableMap::TABLE_NAME)
                    ->addJoinCondition(
                        SpyProductTableMap::TABLE_NAME,
                        SpyProductTableMap::COL_IS_ACTIVE . ' IS TRUE'
                    )
                    ->where(SpyProductTableMap::COL_ID_PRODUCT . ' IS NULL');

                return;
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract|null $productAbstractEntity
     *
     * @return string
     */
    protected function addCheckBox(?SpyProductAbstract $productAbstractEntity = null): string
    {
        $id = $productAbstractEntity ? $productAbstractEntity->getIdProductAbstract() : 'all';

        return sprintf(
            "<input id='%s%d' class='product_abstract_select_checkbox' type='checkbox'>",
            static::SELECT_CHECKBOX_ID_PREFIX,
            $id
        );
    }
}
