<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ApiLog;

use Generated\Shared\Transfer\ApiInboundLogTransfer;
use Generated\Shared\Transfer\ApiOutboundLogTransfer;

interface ApiLogClientInterface
{
    /**
     * Create API outbound log via queue
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiOutboundLogTransfer $apiOutboundLogTransfer
     *
     * @return void
     */
    public function createApiOutboundLogViaQueue(ApiOutboundLogTransfer $apiOutboundLogTransfer): void;

    /**
     * Create API inbound log via queue
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiInboundLogTransfer $apiInboundLogTransfer
     *
     * @return void
     */
    public function createApiInboundLogViaQueue(ApiInboundLogTransfer $apiInboundLogTransfer): void;
}
