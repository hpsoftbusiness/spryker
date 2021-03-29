<?php

namespace Pyz\Zed\ProductApi\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

class ProductApiQueryContainer extends AbstractQueryContainer implements ProductApiQueryContainerInterface
{
    protected const PRODUCTS_LIMIT = 25;

    /**
     * @param int $idProductAbstract
     *
     * @return SpyProductAbstractQuery
     */
    public function queryGet(int $idProductAbstract): SpyProductAbstractQuery
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIdProductAbstract($idProductAbstract);
    }

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryRegularProducts(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.benefit_store') IS NOT TRUE")
            ->where("JSON_VALUE(attributes, '$.shopping_point_store') IS NOT TRUE");
    }

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryBvDeals(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.benefit_store') IS TRUE");
    }

    /**
     * @return SpyProductAbstractQuery
     */
    public function querySpDeals(): SpyProductAbstractQuery
    {
        return $this->queryFind()
            ->where("JSON_VALUE(attributes, '$.shopping_point_store') IS TRUE");
    }

    /**
     * @return SpyProductAbstractQuery
     */
    protected function queryFind(): SpyProductAbstractQuery
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->limit(static::PRODUCTS_LIMIT);
    }
}
