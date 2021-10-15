<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Persistence;

interface WeclappRepositoryInterface
{
    /**
     * @param int $limit
     *
     * @return array
     */
    public function getExistingProductsIdsToExport(int $limit): array;
}
