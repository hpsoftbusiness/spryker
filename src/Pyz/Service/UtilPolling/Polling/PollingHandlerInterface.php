<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilPolling\Polling;

interface PollingHandlerInterface
{
    /**
     * @param callable $requestFunction
     * @param callable $breakCondition
     * @param int $timeInterval
     * @param int $executionTimeLimit
     *
     * @return mixed
     */
    public function handlePolling(
        callable $requestFunction,
        callable $breakCondition,
        int $timeInterval,
        int $executionTimeLimit
    );
}
