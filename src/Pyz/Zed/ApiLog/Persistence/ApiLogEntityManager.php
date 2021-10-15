<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Persistence;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Generated\Shared\Transfer\ApiOutboundLogTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\ApiLog\Persistence\ApiLogPersistenceFactory getFactory()
 */
class ApiLogEntityManager extends AbstractEntityManager implements ApiLogEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiOutboundLogTransfer $apiOutboundLogTransfer
     *
     * @return \Generated\Shared\Transfer\ApiOutboundLogTransfer
     */
    public function createApiOutboundLog(ApiOutboundLogTransfer $apiOutboundLogTransfer): ApiOutboundLogTransfer
    {
        $apiOutboundLogEntity = $this->getFactory()
            ->createApiOutboundLogMapper()
            ->mapEntityTransferToEntity($apiOutboundLogTransfer);
        $apiOutboundLogEntity->save();

        return $this->getFactory()
            ->createApiOutboundLogMapper()
            ->mapEntityToEntityTransfer($apiOutboundLogEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiInboundLogTransfer $apiInboundLogTransfer
     *
     * @return \Generated\Shared\Transfer\ApiInboundLogTransfer
     */
    public function createApiInboundLog(ApiInboundLogTransfer $apiInboundLogTransfer): ApiInboundLogTransfer
    {
        $apiInboundLogEntity = $this->getFactory()
            ->createApiInboundLogMapper()
            ->mapEntityTransferToEntity($apiInboundLogTransfer);
        $apiInboundLogEntity->save();

        return $this->getFactory()
            ->createApiInboundLogMapper()
            ->mapEntityToEntityTransfer($apiInboundLogEntity);
    }
}
