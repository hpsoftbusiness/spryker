<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\WeclappRestApi\Controller;

use Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequest;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Pyz\Glue\WeclappRestApi\WeclappRestApiFactory getFactory()
 */
class WeclappWebhooksResourceController extends AbstractController
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequest $restRequest
     * @param \Generated\Shared\Transfer\RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function postAction(
        RestRequest $restRequest,
        RestWeclappWebhooksAttributesTransfer $restWeclappWebhooksAttributesTransfer
    ): RestResponseInterface {
         return $this->getFactory()
             ->createWeclappWebhooksProcessor()
             ->processWeclappWebhook($restWeclappWebhooksAttributesTransfer, $restRequest->getHttpRequest());
    }
}
