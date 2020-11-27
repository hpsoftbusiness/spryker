<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Table;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroupProductList\Persistence\Map\PyzCustomerGroupToProductListTableMap;
use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList;
use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductListTable extends AbstractTable
{
    protected const ACTIONS = 'Actions';

    protected const COL_FK_PRODUCT_LIST = 'fk_product_list';
    protected const COL_TITLE = 'title';
    protected const COL_TYPE = 'type';

    /**
     * @var \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    protected $customerGroupTransfer;

    /**
     * @param \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerQueryContainer
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     */
    public function __construct(
        CustomerGroupQueryContainerInterface $customerQueryContainer,
        CustomerGroupTransfer $customerGroupTransfer
    ) {
        $this->customerGroupQueryContainer = $customerQueryContainer;
        $this->customerGroupTransfer = $customerGroupTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_FK_PRODUCT_LIST => '#',
            static::COL_TITLE => 'Title',
            static::COL_TYPE => 'Type',
            static::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            static::COL_FK_PRODUCT_LIST,
            static::COL_TITLE,
            static::COL_TYPE,
        ]);

        $config->setUrl(sprintf('table-product-list?id-customer-group=%d', $this->customerGroupTransfer->getIdCustomerGroup()));

        $config->setSearchable([
            PyzCustomerGroupToProductListTableMap::COL_FK_PRODUCT_LIST,
            SpyProductListTableMap::COL_TITLE,
            SpyProductListTableMap::COL_TYPE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed[]
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();

        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList[] $productListCollection */
        $productListCollection = $this->runQuery($query, $config, true);

        if ($productListCollection->count() < 1) {
            return [];
        }

        return $this->mapCustomerGroupCollection($productListCollection);
    }

    /**
     * @param \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList $customerGroupToProductListEntity
     *
     * @return string
     */
    protected function buildLinks(PyzCustomerGroupToProductList $customerGroupToProductListEntity): string
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton(
            sprintf('/product-list-gui/edit?id-product-list=%d', $customerGroupToProductListEntity->getFkProductList()),
            'Edit'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList[] $productListCollection
     *
     * @return mixed[]
     */
    protected function mapCustomerGroupCollection(ObjectCollection $productListCollection): array
    {
        $productListData = [];

        foreach ($productListCollection as $customerGroupToProductListEntity) {
            $productListData[] = $this->mapProductListRow($customerGroupToProductListEntity);
        }

        return $productListData;
    }

    /**
     * @param \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList $customerGroupToProductListEntity
     *
     * @return mixed[]
     */
    protected function mapProductListRow(PyzCustomerGroupToProductList $customerGroupToProductListEntity): array
    {
        $productListRow = $customerGroupToProductListEntity->toArray();

        $productListRow[static::COL_TITLE] = $customerGroupToProductListEntity->getProductList()->getTitle();
        $productListRow[static::COL_TYPE] = $customerGroupToProductListEntity->getProductList()->getType();
        $productListRow[static::ACTIONS] = $this->buildLinks($customerGroupToProductListEntity);

        return $productListRow;
    }

    /**
     * @return \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery
     */
    protected function prepareQuery(): PyzCustomerGroupToProductListQuery
    {
        return $this->customerGroupQueryContainer
            ->queryCustomerGroupToProductListByFkCustomerGroup($this->customerGroupTransfer->getIdCustomerGroup())
            ->leftJoinWithProductList();
    }
}
