<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\UtilPolling\Polling;

class PollingHandler implements PollingHandlerInterface
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
    ) {
        $startTime = $this->getCurrentTime();
        $response = null;
        while ($executionTimeLimit > $this->getPassedTime($startTime)) {
            $response = $requestFunction();
            if ($this->assertBreakCondition($response, $breakCondition)) {
                break;
            }

            sleep($timeInterval);
        }

        return $response;
    }

    /**
     * @param mixed $response
     * @param callable $breakCondition
     *
     * @return bool
     */
    private function assertBreakCondition($response, callable $breakCondition): bool
    {
        return (bool)$breakCondition($response);
    }

    /**
     * @param float $startTime
     *
     * @return float
     */
    private function getPassedTime(float $startTime): float
    {
        return ($this->getCurrentTime() - $startTime);
    }

    /**
     * @return float
     */
    private function getCurrentTime(): float
    {
        return microtime(true);
    }
}
