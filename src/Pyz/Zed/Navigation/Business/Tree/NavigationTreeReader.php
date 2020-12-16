<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Business\Tree;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery;
use Orm\Zed\ProductListStorage\Persistence\Map\SpyProductAbstractProductListStorageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeReader as SprykerNavigationTreeReader;

class NavigationTreeReader extends SprykerNavigationTreeReader
{
    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRootNavigationNodes(SpyNavigation $navigationEntity)
    {
        return $this->addCustomerIdsToSpyNavigationNodeQuery(
            $this->navigationQueryContainer
                ->queryRootNavigationNodesByIdNavigation($navigationEntity->getIdNavigation())
        )->find();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findChildrenNavigationNodes(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->addCustomerIdsToSpyNavigationNodeQuery(
            $this->navigationQueryContainer
                ->queryNavigationNodesByFkParentNavigationNode($navigationNodeTransfer->getIdNavigationNode())
        )->find();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery $spyNavigationNodeQuery
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    protected function addCustomerIdsToSpyNavigationNodeQuery(
        SpyNavigationNodeQuery $spyNavigationNodeQuery
    ): SpyNavigationNodeQuery {
        return $spyNavigationNodeQuery
            ->groupByNodeKey()
            ->useSpyNavigationNodeLocalizedAttributesQuery()
                ->useSpyUrlQuery()
                    ->useSpyCategoryNodeQuery()
                        ->useCategoryQuery(null, Criteria::LEFT_JOIN)
                            ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                                ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                                    ->leftJoinSpyProductAbstractProductListStorage()
                                ->endUse()
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addAsColumn(NavigationNodeTransfer::PRODUCT_ASSIGNMENTS, 'GROUP_CONCAT(' . SpyProductAbstractProductListStorageTableMap::COL_DATA . ')');
    }
}
