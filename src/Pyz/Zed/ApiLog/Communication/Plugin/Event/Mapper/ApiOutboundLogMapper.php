<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Communication\Plugin\Event\Mapper;

use Generated\Shared\Transfer\ApiOutboundLogTransfer;

class ApiOutboundLogMapper implements ApiOutboundLogMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     *
     * @return \Generated\Shared\Transfer\ApiOutboundLogTransfer[]
     */
    public function mapTransfersToApiOutboundLogTransfers(array $transfers): array
    {
        $apiOutboundLogTransfers = [];
        foreach ($transfers as $transfer) {
            $apiOutboundLogTransfers[] = (new ApiOutboundLogTransfer())->fromArray(
                $transfer->getOriginalValues()[ApiOutboundLogTransfer::class] ?? []
            );
        }

        return $apiOutboundLogTransfers;
    }
}
