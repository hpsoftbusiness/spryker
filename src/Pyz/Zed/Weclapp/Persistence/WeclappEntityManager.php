<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Persistence;

use Orm\Zed\Weclapp\Persistence\Map\PyzWeclappExportTableMap;
use Propel\Runtime\Propel;
use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappPersistenceFactory getFactory()
 */
class WeclappEntityManager extends AbstractEntityManager implements WeclappEntityManagerInterface
{
    use MariaDbDataFormatterTrait;

    /**
     * @param array $entriesIds
     * @param string $entryType
     *
     * @return void
     */
    public function insertWeclappExports(array $entriesIds, string $entryType): void
    {
        $params = [];
        $questionMarks = [];
        foreach ($entriesIds as $entryId) {
            $questionMarks[] = '(?, ?)';
            $params = array_merge($params, [$entryId, $entryType]);
        }
        $query = 'INSERT INTO pyz_weclapp_export (fk_entry, entry_type) VALUES '
            . implode(', ', $questionMarks);

        $this->executeQuery($query, $params);
    }

    /**
     * @param string $query
     * @param array $params
     *
     * @return void
     */
    protected function executeQuery(string $query, array $params): void
    {
        $connection = Propel::getWriteConnection(PyzWeclappExportTableMap::DATABASE_NAME);
        $stmt = $connection->prepare($query);
        $stmt->execute($params);
    }
}
