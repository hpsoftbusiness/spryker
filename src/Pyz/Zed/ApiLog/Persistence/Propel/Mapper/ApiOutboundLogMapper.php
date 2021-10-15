<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ApiOutboundLogTransfer;
use Orm\Zed\ApiLog\Persistence\PyzApiOutboundLog;

class ApiOutboundLogMapper implements ApiOutboundLogMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiOutboundLogTransfer $apiOutboundLogTransfer
     *
     * @return \Orm\Zed\ApiLog\Persistence\PyzApiOutboundLog
     */
    public function mapEntityTransferToEntity(ApiOutboundLogTransfer $apiOutboundLogTransfer): PyzApiOutboundLog
    {
        $entity = new PyzApiOutboundLog();
        $entity->fromArray($apiOutboundLogTransfer->toArray());

        return $entity;
    }

    /**
     * @param \Orm\Zed\ApiLog\Persistence\PyzApiOutboundLog $apiOutboundLogEntity
     *
     * @return \Generated\Shared\Transfer\ApiOutboundLogTransfer
     */
    public function mapEntityToEntityTransfer(PyzApiOutboundLog $apiOutboundLogEntity): ApiOutboundLogTransfer
    {
        $entityTransfer = new ApiOutboundLogTransfer();
        $entityTransfer->fromArray($apiOutboundLogEntity->toArray(), true);

        return $entityTransfer;
    }
}
