<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Queue;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\Queue\QueueClient as SprykerQueueClient;

/**
 * @method \Pyz\Client\Queue\QueueFactory getFactory()
 */
class QueueClient extends SprykerQueueClient implements QueueClientInterface
{
    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessageViaZed(string $queueName, QueueSendMessageTransfer $queueSendMessageTransfer): void
    {
        $this->getFactory()
            ->createQueueZedStub()
            ->sendMessage($queueName, $queueSendMessageTransfer);
    }
}
