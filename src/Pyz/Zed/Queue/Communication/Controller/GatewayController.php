<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Queue\Communication\Controller;

use Generated\Shared\Transfer\QueueNameWithMessageTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Pyz\Zed\Queue\Business\QueueFacadeInterface getFacade()()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QueueNameWithMessageTransfer $queueNameWithMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueNameWithMessageTransfer
     */
    public function sendMessageAction(
        QueueNameWithMessageTransfer $queueNameWithMessageTransfer
    ): QueueNameWithMessageTransfer {
        $this->getFacade()->sendMessage($queueNameWithMessageTransfer);

        return $queueNameWithMessageTransfer;
    }
}
