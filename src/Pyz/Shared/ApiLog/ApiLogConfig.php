<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\ApiLog;

class ApiLogConfig
{
    /**
     * Defines queue name as used when with asynchronous event handling.
     */
    public const API_LOG_QUEUE = 'api_log';
}
