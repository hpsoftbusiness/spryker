<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Business;

use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Generated\Shared\Transfer\QueueNameWithMessageTransfer;
use Spryker\Zed\Queue\Business\QueueFacade as SprykerQueueFacade;

/**
 * @method \Pyz\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
class QueueFacade extends SprykerQueueFacade implements QueueFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueDumpRequestTransfer $queueNameRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QueueDumpResponseTransfer
     */
    public function queueDumpForQueueChecker(
        QueueDumpRequestTransfer $queueNameRequestTransfer
    ): QueueDumpResponseTransfer {
        return $this->getFactory()
            ->createQueueDumperForQueueChecker()
            ->dumpQueue($queueNameRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueNameWithMessageTransfer $queueNameWithMessageTransfer
     *
     * @return void
     */
    public function sendMessage(QueueNameWithMessageTransfer $queueNameWithMessageTransfer): void
    {
        $this->getFactory()
            ->createQueueSender()
            ->sendMessage($queueNameWithMessageTransfer);
    }
}
