<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi\Processor\Webhooks;

use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Request;

interface WeclappWebhooksProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function processWeclappWebhook(
        RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer,
        Request $request
    ): RestResponseInterface;
}
