<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\TableDataExpander;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class UserStoreTableDataExpander
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(StoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [
            SpyUserTableMap::COL_FK_STORE => $this->createStoreLabel($item[SpyUserTableMap::COL_FK_STORE]),
        ];
    }

    /**
     * @param int|null $idStore
     *
     * @return string
     */
    protected function createStoreLabel(?int $idStore): string
    {
        if (!$idStore) {
            return '';
        }
        $storeTransfer = $this->storeFacade->getStoreById($idStore);

        return sprintf(
            '<span class="label label-info">%s</span>',
            $storeTransfer->getName()
        );
    }
}
