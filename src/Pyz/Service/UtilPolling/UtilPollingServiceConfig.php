<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilPolling;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilPollingServiceConfig extends AbstractBundleConfig
{
    /**
     * Time limit in seconds.
     */
    public const DEFAULT_POLLING_TIMEOUT = 30;

    /**
     * Time interval between polling function invocations in seconds.
     */
    public const DEFAULT_POLLING_INTERVAL = 3;
}
