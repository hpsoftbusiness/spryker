<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerConfig;

use Spryker\Shared\Kernel\CodeBucket\Config\AbstractCodeBucketConfig;

class CodeBucketConfig extends AbstractCodeBucketConfig
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        return [
            'DE',
            'AT',
            'US',
            'IT',
            'PT',
            'PL',
            'RO',
            'SK',
            'SL',
            'NL',
            'CH',
            'FR',
            'GR',
            'IE',
            'GB',
            'HU',
            'LV',
            'LT',
            'BG',
            'HR',
            'CZ',
            'BE',
            'EE',
            'ES',
            'NO',
            'SE',
            'FI',
            'DK',
            'MK',
            'AL',
            'XK',
            'MD',
            'SI',
            'BA',
            'RS',
            'ME',
            'CA',
            'BR',
            'CO',
            'MX',
            'MY',
            'HK',
            'AU',
            'NZ',
            'PH',
            'TH',
            'MO',
            'BY',
            'UA',
            'RU',
        ];
    }

    /**
     * @deprecated This method implementation will be removed when environment configs are cleaned up.
     *
     * @return string
     */
    public function getDefaultCodeBucket(): string
    {
        return APPLICATION_STORE;
    }
}
