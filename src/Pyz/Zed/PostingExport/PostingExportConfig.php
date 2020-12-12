<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PostingExportConfig extends AbstractBundleConfig
{
    public const DATE_FORMAT = 'd.m.Y';
    public const FILE_NAME_DELIMITER = '-';

    /**
     * @return string[]
     */
    public function getCountryIso2CodeToBusinessPostingGroupMap(): array
    {
        return [
            'AT' => 'DO',
            'IT' => 'IT_UST',
            'DE' => 'DE_UST',
        ];
    }
}
