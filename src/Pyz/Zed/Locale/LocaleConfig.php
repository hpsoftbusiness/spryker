<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Locale;

use Spryker\Zed\Locale\LocaleConfig as SprykerLocaleConfig;

class LocaleConfig extends SprykerLocaleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getLocaleFile()
    {
        return realpath(__DIR__ . '/Business/Internal/Install/locales.txt');
    }
}
