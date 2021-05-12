<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Service\UtilPolling;

use Codeception\Test\Unit;
use Pyz\Service\UtilPolling\UtilPollingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Service
 * @group UtilPolling
 * @group UtilPollingServiceTest
 * Add your own group annotations below this line
 */
class UtilPollingServiceTest extends Unit
{
    private const ITERATION_INTERVAL = 1;
    private const EXECUTION_TIME_LIMIT = 3;

    /**
     * @var \PyzTest\Service\UtilPolling\UtilPollingServiceTester
     */
    protected $tester;

    /**
     * @var \Pyz\Service\UtilPolling\UtilPollingServiceInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->getService();
    }

    /**
     * @return void
     */
    public function testPollingCallbackResultReturnedWhenBreakConditionPasses(): void
    {
        $iterations = 0;
        $pollingCallback = static function () use (&$iterations) {
            $iterations++;

            return $iterations;
        };

        $breakCondition = function () use (&$iterations) {
            return $iterations === 2;
        };

        $startTime = microtime(true);
        $result = $this->sut->executePollingProcess(
            $pollingCallback,
            $breakCondition,
            self::ITERATION_INTERVAL,
            self::EXECUTION_TIME_LIMIT
        );
        $endTime = microtime(true);

        self::assertEquals(2, $result);
        self::assertLessThan((float)$startTime + self::EXECUTION_TIME_LIMIT, $endTime);
    }

    /**
     * @return void
     */
    public function testLastPollingCallbackResultReturnedWhenTimeLimitRunsOut(): void
    {
        $iterations = 0;
        $pollingCallback = static function () use (&$iterations) {
            $iterations++;

            return $iterations;
        };

        $breakCondition = function () {
            return false;
        };

        $startTime = microtime(true);
        $result = $this->sut->executePollingProcess(
            $pollingCallback,
            $breakCondition,
            self::ITERATION_INTERVAL,
            self::EXECUTION_TIME_LIMIT
        );
        $endTime = microtime(true);

        self::assertEquals(3, $result);

        /**
         * Adding +0.5 second on top of time limit to compensate for code execution time.
         */
        self::assertLessThan((float)$startTime + self::EXECUTION_TIME_LIMIT + 0.5, $endTime);
    }

    /**
     * @return \Pyz\Service\UtilPolling\UtilPollingServiceInterface
     */
    private function getService(): UtilPollingServiceInterface
    {
        return $this->tester->getLocator()->utilPolling()->service();
    }
}
