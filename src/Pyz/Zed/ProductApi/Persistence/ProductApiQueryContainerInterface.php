<?php

namespace Pyz\Zed\ProductApi\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductApiQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return SpyProductAbstractQuery
     */
    public function queryGet(int $idProductAbstract): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryRegularProducts(): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryBvDeals(): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function querySpDeals(): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryEliteClub(): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryOneSense(): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryLyconet(): SpyProductAbstractQuery;

    /**
     * @return SpyProductAbstractQuery
     */
    public function queryFeaturedProducts(): SpyProductAbstractQuery;
}
