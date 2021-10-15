<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Queue;

use Pyz\Client\Queue\Zed\QueueZedStub;
use Pyz\Client\Queue\Zed\QueueZedStubInterface;
use Spryker\Client\Queue\QueueFactory as SprykerQueueFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class QueueFactory extends SprykerQueueFactory
{
    /**
     * @return \Pyz\Client\Queue\Zed\QueueZedStubInterface
     */
    public function createQueueZedStub(): QueueZedStubInterface
    {
        return new QueueZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(QueueDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
