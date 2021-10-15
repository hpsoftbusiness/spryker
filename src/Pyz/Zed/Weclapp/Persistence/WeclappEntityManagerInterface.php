<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Persistence;

interface WeclappEntityManagerInterface
{
    /**
     * @param array $entriesIds
     * @param string $entryType
     *
     * @return void
     */
    public function insertWeclappExports(array $entriesIds, string $entryType): void;
}
