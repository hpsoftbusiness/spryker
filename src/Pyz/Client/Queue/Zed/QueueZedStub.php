<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Queue\Zed;

use Generated\Shared\Transfer\QueueNameWithMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class QueueZedStub implements QueueZedStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage(string $queueName, QueueSendMessageTransfer $queueSendMessageTransfer): void
    {
        $queueNameWithMessageTransfer = new QueueNameWithMessageTransfer();
        $queueNameWithMessageTransfer->setQueueName($queueName)
            ->setQueueMessage($queueSendMessageTransfer);

        $this->zedRequestClient->call(
            '/queue/gateway/send-message',
            $queueNameWithMessageTransfer
        );
    }
}
