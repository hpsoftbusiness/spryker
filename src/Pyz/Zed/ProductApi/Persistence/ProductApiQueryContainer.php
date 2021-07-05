<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\ProductApi\Persistence\ProductApiPersistenceFactory getFactory()
 */
class ProductApiQueryContainer extends AbstractQueryContainer implements ProductApiQueryContainerInterface
{
    protected const PRODUCTS_LIMIT = 100;

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryGet(int $idProductAbstract): SpyProductAbstractQuery
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryRegularProducts(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.benefit_store') IS NOT TRUE")
            ->where("JSON_VALUE(attributes, '$.shopping_point_store') IS NOT TRUE");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryBvDeals(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.benefit_store') IS TRUE");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpDeals(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.shopping_point_store') IS TRUE");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryEliteClub(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.brand') = 'EliteClub'");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryOneSense(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.brand') = 'OneSense'");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryLyconet(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.brand') = 'Lyconet'");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryFeaturedProducts(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.featured_products') IS TRUE");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryEliteClubEcDealOnly(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.ec_deal_only') IS TRUE");
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryFind(): SpyProductAbstractQuery
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIsRemoved(false)
            ->limit(static::PRODUCTS_LIMIT);
    }
}
