<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilPolling;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Pyz\Service\UtilPolling\UtilPollingServiceFactory getFactory()
 */
class UtilPollingService extends AbstractService implements UtilPollingServiceInterface
{
    /**
     * @param callable $requestFunction
     * @param callable $breakCondition
     * @param int $timeInterval
     * @param int $executionTimeLimit
     *
     * @return mixed
     */
    public function executePollingProcess(
        callable $requestFunction,
        callable $breakCondition,
        int $timeInterval = UtilPollingServiceConfig::DEFAULT_POLLING_INTERVAL,
        int $executionTimeLimit = UtilPollingServiceConfig::DEFAULT_POLLING_TIMEOUT
    ) {
        return $this->getFactory()->createPollingHandler()->handlePolling($requestFunction, $breakCondition, $timeInterval, $executionTimeLimit);
    }
}
