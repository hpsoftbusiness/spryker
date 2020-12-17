<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Business\Tree;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery;
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
        return $this->addIdCategoryToSpyNavigationNodeQuery(
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
        return $this->addIdCategoryToSpyNavigationNodeQuery(
            $this->navigationQueryContainer
                ->queryNavigationNodesByFkParentNavigationNode($navigationNodeTransfer->getIdNavigationNode())
        )->find();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery $spyNavigationNodeQuery
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery
     */
    protected function addIdCategoryToSpyNavigationNodeQuery(
        SpyNavigationNodeQuery $spyNavigationNodeQuery
    ): SpyNavigationNodeQuery {
        return $spyNavigationNodeQuery
            ->groupByNodeKey()
            ->useSpyNavigationNodeLocalizedAttributesQuery()
                ->useSpyUrlQuery()
                    ->leftJoinSpyCategoryNode()
                ->endUse()
            ->endUse()
            ->addAsColumn(NavigationNodeTransfer::ID_CATEGORY, SpyCategoryNodeTableMap::COL_FK_CATEGORY);
    }
}
