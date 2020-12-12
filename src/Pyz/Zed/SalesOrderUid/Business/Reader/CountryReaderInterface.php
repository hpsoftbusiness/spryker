<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business\Reader;

interface CountryReaderInterface
{
    /**
     * @param string $salesOrderUid
     *
     * @return string|null
     */
    public function findCountryIso2CodeByUid(string $salesOrderUid): ?string;
}
