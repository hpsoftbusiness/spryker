<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper;

use Generated\Shared\Transfer\ApiInboundLogTransfer;

class ApiInboundLogMapper implements ApiInboundLogMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     *
     * @return \Generated\Shared\Transfer\ApiInboundLogTransfer[]
     */
    public function mapTransfersToApiInboundLogTransfers(array $transfers): array
    {
        $apiInboundLogTransfers = [];
        foreach ($transfers as $transfer) {
            $apiInboundLogTransfers[] = (new ApiInboundLogTransfer())->fromArray(
                $transfer->getOriginalValues()[ApiInboundLogTransfer::class] ?? []
            );
        }

        return $apiInboundLogTransfers;
    }
}
