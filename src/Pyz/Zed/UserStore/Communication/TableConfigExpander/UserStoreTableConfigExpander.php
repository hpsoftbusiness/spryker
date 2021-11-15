<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\TableConfigExpander;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class UserStoreTableConfigExpander
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $this->addStoreHeader($config);
        $this->setRawStoreColumn($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function addStoreHeader(TableConfiguration $config): void
    {
        $header = $this->insertAfterHeader($config->getHeader(), SpyUserTableMap::COL_STATUS, [
            SpyUserTableMap::COL_FK_STORE => 'Store',
        ]);

        $config->setHeader($header);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawStoreColumn(TableConfiguration $config): void
    {
        $config->setRawColumns(array_merge($config->getRawColumns(), [
            SpyUserTableMap::COL_FK_STORE,
        ]));
    }

    /**
     * @param array $array
     * @param string $key
     * @param array $new
     *
     * @return array
     */
    protected function insertAfterHeader(array $array, string $key, array $new): array
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys);
        $pos = $index === false ? count($array) : $index + 1;

        return array_merge(array_slice($array, 0, $pos), $new, array_slice($array, $pos));
    }
}
