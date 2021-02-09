<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PostingExportConfig extends AbstractBundleConfig
{
    public const DATE_FORMAT = 'dmY';
    public const FILE_NAME_DELIMITER = '_';

    /**
     * @return string[]
     */
    public function getCountryIso2CodeToVatBusPostingGroupMap(): array
    {
        return [
            'AT' => 'DO',
            'IT' => 'IT',
            'DE' => 'DE',
            'EE' => 'EE',
            'FI' => 'FI',
            'PL' => 'PL',
            'SK' => 'SK',
            'SI' => 'SI',
            'HU' => 'HU',
        ];
    }

    /**
     * @return string[]
     */
    public function getCountryIso2CodeToGenBusinessPostingGroupMap(): array
    {
        return [
            'AT' => 'DO',
            'IT' => 'IT_UST',
            'DE' => 'DE_UST',
            'EE' => 'EU_UST',
            'FI' => 'FI_UST',
            'PL' => 'PL_UST',
            'SK' => 'SK_UST',
            'SI' => 'SI_UST',
            'HU' => 'HU_UST',
        ];
    }
}
