<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Navigation;

use Spryker\Shared\Navigation\NavigationConfig as SprykerNavigationConfig;

class NavigationConfig extends SprykerNavigationConfig
{
    public const NAVIGATION_KEYS_CATEGORY_DRIVEN = [
        'MAIN_NAVIGATION',
        'MAIN_NAVIGATION_DESKTOP',
    ];
}
