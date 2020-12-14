<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesOrderUidConfig extends AbstractBundleConfig
{
    protected const UID_AUSTRIA = 'ATU67661657';
    protected const UID_ITALY = '0026289999';

    /**
     * @api
     *
     * @return string[]
     */
    public function getCountryToUidMap(): array
    {
        return [
            'AT' => static::UID_AUSTRIA,
            'IT' => static::UID_ITALY,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultUid(): string
    {
        return static::UID_AUSTRIA;
    }
}
