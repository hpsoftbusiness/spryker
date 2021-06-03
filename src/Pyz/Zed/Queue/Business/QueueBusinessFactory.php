<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Business;

use Pyz\Zed\Queue\Business\QueueDumper\QueueDumperForQueueChecker;
use Spryker\Zed\Queue\Business\QueueBusinessFactory as SprykerQueueBusinessFactory;
use Spryker\Zed\Queue\Business\QueueDumper\QueueDumperInterface;

class QueueBusinessFactory extends SprykerQueueBusinessFactory
{
    /**
     * @return \Spryker\Zed\Queue\Business\QueueDumper\QueueDumperInterface
     */
    public function createQueueDumperForQueueChecker(): QueueDumperInterface
    {
        return new QueueDumperForQueueChecker(
            $this->getQueueClient(),
            $this->getConfig(),
            $this->getUtilEncodingService(),
            $this->getProcessorMessagePlugins()
        );
    }
}
