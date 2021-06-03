<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilPolling;

interface UtilPollingServiceInterface
{
    /**
     * Specification:
     * - Keeps executing $requestFunction until $breakCondition is accepted or execution time limit is reached.
     * - $timeInterval is time different between response of the last request and initiation of the new one.
     * - Break condition callable needs to accept one parameter - request function's response and return boolean value.
     * - Returns result of the last $requestFunction invocation.
     *
     * @api
     *
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
    );
}
