<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Business\Sender;

use Generated\Shared\Transfer\QueueNameWithMessageTransfer;
use Pyz\Client\Queue\QueueClientInterface;

class QueueSender implements QueueSenderInterface
{
    /**
     * @var \Pyz\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @param \Pyz\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(QueueClientInterface $queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueNameWithMessageTransfer $queueNameWithMessageTransfer
     *
     * @return void
     */
    public function sendMessage(QueueNameWithMessageTransfer $queueNameWithMessageTransfer): void
    {
        $this->queueClient->sendMessage(
            $queueNameWithMessageTransfer->getQueueNameOrFail(),
            $queueNameWithMessageTransfer->getQueueMessageOrFail()
        );
    }
}
