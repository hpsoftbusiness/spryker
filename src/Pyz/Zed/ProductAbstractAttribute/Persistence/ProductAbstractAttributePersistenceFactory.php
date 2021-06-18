<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductAbstractAttribute\Persistence\PyzProductAbstractAttributeQuery;
use Pyz\Zed\Product\Persistence\ProductQueryContainerInterface;
use Pyz\Zed\ProductAbstractAttribute\Persistence\Propel\Mapper\ProductAbstractAttributeMapper;
use Pyz\Zed\ProductAbstractAttribute\ProductAbstractAttributeDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface getRepository()
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface getEntityManager()
 */
class ProductAbstractAttributePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAbstractAttributeDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductAbstractAttribute\Persistence\PyzProductAbstractAttributeQuery
     */
    public function createProductAbstractAttributeQuery(): PyzProductAbstractAttributeQuery
    {
        return PyzProductAbstractAttributeQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Pyz\Zed\ProductAbstractAttribute\Persistence\Propel\Mapper\ProductAbstractAttributeMapper
     */
    public function createProductAbstractAttributeMapper(): ProductAbstractAttributeMapper
    {
        return new ProductAbstractAttributeMapper();
    }
}
