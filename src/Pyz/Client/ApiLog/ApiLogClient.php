<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ApiLog;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Generated\Shared\Transfer\ApiOutboundLogTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ApiLog\ApiLogFactory getFactory()
 */
class ApiLogClient extends AbstractClient implements ApiLogClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiOutboundLogTransfer $apiOutboundLogTransfer
     *
     * @return void
     */
    public function createApiOutboundLogViaQueue(ApiOutboundLogTransfer $apiOutboundLogTransfer): void
    {
        $this->getFactory()
            ->createApiLogWriter()
            ->createApiOutboundLogViaQueue($apiOutboundLogTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiInboundLogTransfer $apiInboundLogTransfer
     *
     * @return void
     */
    public function createApiInboundLogViaQueue(ApiInboundLogTransfer $apiInboundLogTransfer): void
    {
        $this->getFactory()
            ->createApiLogWriter()
            ->createApiInboundLogViaQueue($apiInboundLogTransfer);
    }
}
