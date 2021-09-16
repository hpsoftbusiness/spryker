<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Config\Application;

use Pyz\Shared\Kernel\Store;
use Spryker\Shared\Config\Application\Environment as SprykerEnvironment;

class Environment extends SprykerEnvironment
{
    /**
     * @return void
     */
    public static function initialize()
    {
        date_default_timezone_set('UTC');

        static::defineEnvironment();
        static::defineStore();
        static::defineCodeBucket();
        static::defineApplication();
        static::defineApplicationRootDir();
        static::defineApplicationSourceDir();
        static::defineApplicationStaticDir();
        static::defineApplicationVendorDir();
        static::defineApplicationDataDir();

        $store = Store::getInstance();
        $locale = current($store->getLocales());

        self::initializeLocale($locale);
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
    }
}
