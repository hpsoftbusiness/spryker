<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesOrderUidConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getCountryToUidMap(): array
    {
        return [
            'AT' => 'ATU67661657',
            'IT' => '0026289999',
        ];
    }
}
