<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Navigation\Persistence;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationTableMap;
use Spryker\Zed\Navigation\Persistence\NavigationRepository as SprykerNavigationRepository;

class NavigationRepository extends SprykerNavigationRepository implements NavigationRepositoryInterface
{
    /**
     * @param string[] $navigationKeys
     *
     * @return int[]
     */
    public function getNavigationIdsByKeys(array $navigationKeys): array
    {
        $navigationQuery = $this->getFactory()->createNavigationQuery()
            ->filterByKey_In($navigationKeys);

        return $navigationQuery->select(SpyNavigationTableMap::COL_ID_NAVIGATION)
            ->find()
            ->toArray();
    }
}
