<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Business\Sender;

use Generated\Shared\Transfer\QueueNameWithMessageTransfer;

interface QueueSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueueNameWithMessageTransfer $queueNameWithMessageTransfer
     *
     * @return void
     */
    public function sendMessage(QueueNameWithMessageTransfer $queueNameWithMessageTransfer): void;
}
