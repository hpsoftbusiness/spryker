<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Pyz\Shared\Weclapp\WeclappConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappPersistenceFactory getFactory()
 */
class WeclappRepository extends AbstractRepository implements WeclappRepositoryInterface
{
    /**
     * @param int $limit
     *
     * @return array
     */
    public function getExistingProductsIdsToExport(int $limit): array
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->where(
                SpyProductTableMap::COL_ID_PRODUCT
                . ' NOT IN ('
                . $this->getWeclappExportEntriesIdsQuery()
                . ')',
                WeclappConfig::WECLAPP_EXPORT_TYPE_PRODUCT
            )
            ->filterByIsRemoved(false)
            ->useSpyProductAbstractQuery()
                ->filterByIsRemoved(false)
                ->filterByIsAffiliate(false)
            ->endUse()
            ->limit($limit)
            ->find()
            ->toArray();
    }

    /**
     * @return string
     */
    protected function getWeclappExportEntriesIdsQuery(): string
    {
        return "SELECT fk_entry FROM pyz_weclapp_export WHERE entry_type = ?";
    }
}
