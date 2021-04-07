<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock;

use Spryker\Zed\Stock\StockConfig as SprykerStockConfig;

class StockConfig extends SprykerStockConfig
{
    public const INTERNAL_WAREHOUSE_AUSTRIA = 'Graz (Austria)';
    public const INTERNAL_WAREHOUSE_GERMANY = 'Hannover (Germany)';
    public const EXTERNAL_AFFILIATE_WAREHOUSE = 'Affiliate';

     /**
      * @return array
      */
    public function getStoreToWarehouseMapping()
    {
        return [
            'DE' => [
                static::INTERNAL_WAREHOUSE_AUSTRIA,
                static::INTERNAL_WAREHOUSE_GERMANY,
                static::EXTERNAL_AFFILIATE_WAREHOUSE,
            ],
        ];
    }
}
