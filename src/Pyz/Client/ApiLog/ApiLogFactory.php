<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ApiLog;

use Pyz\Client\ApiLog\Writer\ApiLogWriter;
use Pyz\Client\ApiLog\Writer\ApiLogWriterInterface;
use Pyz\Client\Queue\QueueClientInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ApiLogFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\ApiLog\Writer\ApiLogWriterInterface
     */
    public function createApiLogWriter(): ApiLogWriterInterface
    {
        return new ApiLogWriter($this->getQueueClient());
    }

    /**
     * @return \Pyz\Client\Queue\QueueClientInterface
     */
    protected function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(ApiLogDependencyProvider::CLIENT_QUEUE);
    }
}
