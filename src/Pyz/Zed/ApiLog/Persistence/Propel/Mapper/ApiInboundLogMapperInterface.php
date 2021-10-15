<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Orm\Zed\ApiLog\Persistence\PyzApiInboundLog;

interface ApiInboundLogMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiInboundLogTransfer $apiInboundLogTransfer
     *
     * @return \Orm\Zed\ApiLog\Persistence\PyzApiInboundLog
     */
    public function mapEntityTransferToEntity(ApiInboundLogTransfer $apiInboundLogTransfer): PyzApiInboundLog;

    /**
     * @param \Orm\Zed\ApiLog\Persistence\PyzApiInboundLog $apiInboundLogEntity
     *
     * @return \Generated\Shared\Transfer\ApiInboundLogTransfer
     */
    public function mapEntityToEntityTransfer(PyzApiInboundLog $apiInboundLogEntity): ApiInboundLogTransfer;
}
