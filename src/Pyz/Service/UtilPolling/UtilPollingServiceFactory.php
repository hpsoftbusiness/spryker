<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilPolling;

use Pyz\Service\UtilPolling\Polling\PollingHandler;
use Pyz\Service\UtilPolling\Polling\PollingHandlerInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class UtilPollingServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Pyz\Service\UtilPolling\Polling\PollingHandlerInterface
     */
    public function createPollingHandler(): PollingHandlerInterface
    {
        return new PollingHandler();
    }
}
