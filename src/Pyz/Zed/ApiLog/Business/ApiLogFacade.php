<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ApiLog\Business;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Generated\Shared\Transfer\ApiOutboundLogTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ApiLog\Persistence\ApiLogEntityManagerInterface getEntityManager()
 */
class ApiLogFacade extends AbstractFacade implements ApiLogFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiOutboundLogTransfer $apiOutboundLogTransfer
     *
     * @return \Generated\Shared\Transfer\ApiOutboundLogTransfer
     */
    public function createApiOutboundLog(ApiOutboundLogTransfer $apiOutboundLogTransfer): ApiOutboundLogTransfer
    {
        return $this->getEntityManager()->createApiOutboundLog($apiOutboundLogTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiInboundLogTransfer $apiInboundLogTransfer
     *
     * @return \Generated\Shared\Transfer\ApiInboundLogTransfer
     */
    public function createApiInboundLog(ApiInboundLogTransfer $apiInboundLogTransfer): ApiInboundLogTransfer
    {
        return $this->getEntityManager()->createApiInboundLog($apiInboundLogTransfer);
    }
}
