<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Weclapp;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class WeclappConfig extends AbstractSharedConfig
{
    /**
     * Defines queue name as used when with asynchronous event handling.
     */
    public const WECLAPP_QUEUE = 'weclapp';

    public const LOCALE_CODE = 'en_US';

    public const WECLAPP_EXPORT_TYPE_PRODUCT = 'product';
}
