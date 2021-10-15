<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ApiLog\Communication\ApiLogCommunicationFactory getFactory()
 * @method \Pyz\Zed\ApiLog\Business\ApiLogFacadeInterface getFacade()
 */
class NewApiInboundLogBulkListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName)
    {
        $apiInboundLogTransfers = $this->getFactory()
            ->createApiInboundLogMapper()
            ->mapTransfersToApiInboundLogTransfers($transfers);

        foreach ($apiInboundLogTransfers as $apiInboundLogTransfer) {
            $this->getFacade()->createApiInboundLog($apiInboundLogTransfer);
        }
    }
}
