<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Business;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Generated\Shared\Transfer\QueueNameWithMessageTransfer;
use Spryker\Zed\Queue\Business\QueueFacadeInterface as SprykerQueueFacadeInterface;

/**
 * @method \Pyz\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
interface QueueFacadeInterface extends SprykerQueueFacadeInterface
{
    /**
     * Specification:
     *  - Only for Queue Checker, without checkQueuePluginProcessor.
     *  - Reads messages from the specific queue.
     *  - Gets queue name, limit, acknowledge and format from the transfer object.
     *  - Throws an exception if event doesn't exist.
     *  - Returns transfer object with dumped amount of messages in the defined output format.
     *  - Only for Queue Checker, without checkQueuePluginProcessor.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueNameRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function queueDumpForQueueChecker(
        QueueDumpRequestTransfer $queueNameRequestTransfer
    ): QueueDumpResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QueueNameWithMessageTransfer $queueNameWithMessageTransfer
     *
     * @return void
     */
    public function sendMessage(QueueNameWithMessageTransfer $queueNameWithMessageTransfer): void;
}
