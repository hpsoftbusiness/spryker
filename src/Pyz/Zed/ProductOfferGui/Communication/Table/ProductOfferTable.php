<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductOfferGui\Communication\Table\ProductOfferTable as SprykerProductOfferTable;

class ProductOfferTable extends SprykerProductOfferTable
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $url = Url::generate(
            '/table',
            $this->getRequest()->query->all()
        );
        $config->setUrl($url);

        $config = $this->setHeader($config);

        $config->setSortable(
            [
                SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER,
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                static::COL_PRODUCT_NAME,
                SpyProductOfferTableMap::COL_APPROVAL_STATUS,
                SpyProductOfferTableMap::COL_IS_ACTIVE,
            ]
        );

        $config->setRawColumns(
            [
                static::COL_ACTIONS,
                SpyProductOfferTableMap::COL_APPROVAL_STATUS,
                SpyProductOfferTableMap::COL_IS_ACTIVE,
            ]
        );
        $config->setDefaultSortField(SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER, TableConfiguration::SORT_DESC);

        $config->setSearchable(
            [
                SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER,
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE,
                SpyProductOfferTableMap::COL_CONCRETE_SKU,
                SpyProductLocalizedAttributesTableMap::COL_NAME,
                SpyProductOfferTableMap::COL_APPROVAL_STATUS,
                SpyProductOfferTableMap::COL_IS_ACTIVE,
            ]
        );

        foreach ($this->productOfferTableExpanderPlugins as $productOfferTableExpanderPlugin) {
            $config = $productOfferTableExpanderPlugin->expandTableConfiguration($config);
        }

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER => 'Offer ID',
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => 'Reference',
            SpyProductOfferTableMap::COL_CONCRETE_SKU => 'SKU',
            static::COL_PRODUCT_NAME => 'Name',
            SpyProductOfferTableMap::COL_APPROVAL_STATUS => 'Status',
            SpyProductOfferTableMap::COL_IS_ACTIVE => 'Visibility',
            static::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($baseData);

        return $config;
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function prepareQuery(): SpyProductOfferQuery
    {
        $this->productOfferQuery = $this->repository->mapQueryCriteriaTransferToModelCriteria(
            $this->productOfferQuery,
            $this->buildQueryCriteriaTransfer()
        );

        $this->productOfferQuery
            ->addJoin(SpyProductOfferTableMap::COL_CONCRETE_SKU, SpyProductTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->addJoin(
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT,
                Criteria::INNER_JOIN
            )
            ->where(
                sprintf(
                    '%s = (%s)',
                    SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE,
                    $this->localeFacade->getCurrentLocale()->getIdLocale()
                )
            )
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_PRODUCT_NAME);

        return $this->productOfferQuery;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $item) {
            $rowData = [
                SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER => $item[SpyProductOfferTableMap::COL_ID_PRODUCT_OFFER],
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $item[SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE],
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $item[SpyProductOfferTableMap::COL_CONCRETE_SKU],
                static::COL_PRODUCT_NAME => $item[static::COL_PRODUCT_NAME],
                SpyProductOfferTableMap::COL_APPROVAL_STATUS => $this->createStatusLabel($item),
                SpyProductOfferTableMap::COL_IS_ACTIVE => $this->getActiveLabel(
                    $item[SpyProductOfferTableMap::COL_IS_ACTIVE]
                ),
                static::COL_ACTIONS => $this->buildLinks($item),
            ];

            foreach ($this->productOfferTableExpanderPlugins as $productOfferTableExpanderPlugin) {
                $rowData = $productOfferTableExpanderPlugin->expandData($rowData, $item);
            }

            $results[] = $rowData;
        }

        return $results;
    }
}
