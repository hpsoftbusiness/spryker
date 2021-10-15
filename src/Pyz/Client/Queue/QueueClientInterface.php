<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Queue;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\Queue\QueueClientInterface as SprykerQueueClientInterface;

interface QueueClientInterface extends SprykerQueueClientInterface
{
    /**
     * Send message via Zed
     *
     * @api
     *
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessageViaZed(string $queueName, QueueSendMessageTransfer $queueSendMessageTransfer): void;
}
