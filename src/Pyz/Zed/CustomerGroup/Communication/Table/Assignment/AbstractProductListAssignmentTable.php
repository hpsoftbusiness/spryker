<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Table\Assignment;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductList;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class AbstractProductListAssignmentTable extends AbstractTable
{
    public const PARAM_ID_CUSTOMER_GROUP = 'id-customer-group';

    public const COL_SELECT_CHECKBOX = 'select-checkbox';
    public const COL_ID_PRODUCT_LIST = SpyProductListTableMap::COL_ID_PRODUCT_LIST;
    public const COL_TITLE = SpyProductListTableMap::COL_TITLE;
    public const COL_TYPE = SpyProductListTableMap::COL_TYPE;

    /**
     * @var \Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentProductListQueryBuilderInterface
     */
    protected $tableQueryBuilder;

    /**
     * @var int|null
     */
    protected $idCustomerGroup;

    /**
     * @param \Pyz\Zed\CustomerGroup\Communication\Table\Assignment\AssignmentProductListQueryBuilderInterface $tableQueryBuilder
     * @param \Spryker\Zed\CustomerGroup\Dependency\Service\CustomerGroupToUtilEncodingInterface $utilEncoding
     * @param int|null $idCustomerGroup
     */
    public function __construct(
        AssignmentProductListQueryBuilderInterface $tableQueryBuilder,
        CustomerGroupToUtilEncodingInterface $utilEncoding,
        ?int $idCustomerGroup = null
    ) {
        $this->tableQueryBuilder = $tableQueryBuilder;
        $this->utilEncoding = $utilEncoding;
        $this->idCustomerGroup = $idCustomerGroup;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->configureHeader($config);
        $this->configureRawColumns($config);
        $this->configureSorting($config);
        $this->configureSearching($config);
        $this->configureUrl($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config): void
    {
        $config->setHeader(
            [
                static::COL_SELECT_CHECKBOX => 'Select',
                static::COL_ID_PRODUCT_LIST => 'ID',
                static::COL_TITLE => 'Title',
                static::COL_TYPE => 'Type',
            ]
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureRawColumns(TableConfiguration $config): void
    {
        $config->setRawColumns(
            [
                static::COL_SELECT_CHECKBOX,
            ]
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSorting(TableConfiguration $config): void
    {
        $config->setDefaultSortField(
            static::COL_ID_PRODUCT_LIST,
            TableConfiguration::SORT_ASC
        );

        $config->setSortable(
            [
                static::COL_ID_PRODUCT_LIST,
                static::COL_TITLE,
                static::COL_TYPE,
            ]
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearching(TableConfiguration $config): void
    {
        $config->setSearchable(
            [
                static::COL_ID_PRODUCT_LIST,
                static::COL_TITLE,
                static::COL_TYPE,
            ]
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureUrl(TableConfiguration $config): void
    {
        $config->setUrl(
            sprintf(
                '%s?%s=%s',
                $this->defaultUrl,
                static::PARAM_ID_CUSTOMER_GROUP,
                $this->idCustomerGroup
            )
        );
    }

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity
     *
     * @return mixed[]
     */
    protected function getRow(SpyProductList $productListEntity): array
    {
        return [
            static::COL_SELECT_CHECKBOX => $this->getSelectCheckboxColumn($productListEntity),
            static::COL_ID_PRODUCT_LIST => $productListEntity->getIdProductList(),
            static::COL_TITLE => $productListEntity->getTitle(),
            static::COL_TYPE => $productListEntity->getType(),
        ];
    }

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductListQuery[]|\Propel\Runtime\Collection\ObjectCollection $customerEntities
     *
     * @return mixed[]
     */
    protected function buildResultData(ObjectCollection $customerEntities): array
    {
        $tableRows = [];
        /** @var \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity */
        foreach ($customerEntities as $productListEntity) {
            $tableRows[] = $this->getRow($productListEntity);
        }

        return $tableRows;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->getQuery();

        /** @var \Orm\Zed\ProductList\Persistence\SpyProductListQuery[]|\Propel\Runtime\Collection\ObjectCollection $productListEntities */
        $productListEntities = $this->runQuery($query, $config, true);
        $rows = $this->buildResultData($productListEntities);

        return $rows;
    }

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity
     *
     * @return string
     */
    protected function getSelectCheckboxColumn(SpyProductList $productListEntity): string
    {
        return sprintf(
            '<input class="%s" type="checkbox" name="productList[]" value="%s" %s data-info="%s"/>',
            'js-product-list-checkbox',
            $productListEntity->getIdProductList(),
            $this->getCheckboxCheckedAttribute(),
            htmlspecialchars(
                $this->utilEncoding->encodeJson(
                    [
                        'id' => $productListEntity->getIdProductList(),
                        'title' => $productListEntity->getTitle(),
                        'type' => $productListEntity->getType(),
                    ]
                )
            )
        );
    }

    /**
     * @return string
     */
    abstract protected function getCheckboxCheckedAttribute(): string;

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    abstract protected function getQuery(): SpyProductListQuery;
}
