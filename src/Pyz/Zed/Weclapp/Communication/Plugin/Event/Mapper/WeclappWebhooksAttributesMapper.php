<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Plugin\Event\Mapper;

use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;

class WeclappWebhooksAttributesMapper implements WeclappWebhooksAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     *
     * @return \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer[]
     */
    public function mapTransfersToRestWeclappWebhooksAttributesTransfer(array $transfers): array
    {
        $restWeclappWebhooksAttributesTransfers = [];
        foreach ($transfers as $transfer) {
            $restWeclappWebhooksAttributesTransfers[] = (new RestWeclappWebhooksAttributesTransfer())->fromArray(
                $transfer->getOriginalValues()[RestWeclappWebhooksAttributesTransfer::class] ?? []
            );
        }

        return $restWeclappWebhooksAttributesTransfers;
    }
}
