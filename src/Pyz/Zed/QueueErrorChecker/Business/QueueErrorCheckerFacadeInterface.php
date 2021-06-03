<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\QueueErrorChecker\Business;

interface QueueErrorCheckerFacadeInterface
{
    /**
     * @param string $queueName
     * @param int $limit
     *
     * @return array
     */
    public function getQueueErrorMessages(string $queueName, int $limit): array;
}
