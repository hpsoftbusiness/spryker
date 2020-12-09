<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business\Reader;

use Pyz\Zed\SalesOrderUid\SalesOrderUidConfig;

class CountryReader implements CountryReaderInterface
{
    /**
     * @var \Pyz\Zed\SalesOrderUid\SalesOrderUidConfig
     */
    protected $salesOrderUidConfig;

    /**
     * @param \Pyz\Zed\SalesOrderUid\SalesOrderUidConfig $salesOrderUidConfig
     */
    public function __construct(SalesOrderUidConfig $salesOrderUidConfig)
    {
        $this->salesOrderUidConfig = $salesOrderUidConfig;
    }

    /**
     * @param string $salesOrderUid
     *
     * @return string|null
     */
    public function findCountryIso2CodeByUid(string $salesOrderUid): ?string
    {
        $countryToIdMap = $this->salesOrderUidConfig->getCountryToUidMap();
        $countryIso2Code = array_search($salesOrderUid, $countryToIdMap);

        if ($countryIso2Code === false) {
            return null;
        }

        return $countryIso2Code;
    }
}
