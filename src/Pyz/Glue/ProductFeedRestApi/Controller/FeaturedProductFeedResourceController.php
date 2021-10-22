<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Pyz\Glue\ProductFeedRestApi\ProductFeedRestApiFactory getFactory()
 */
class FeaturedProductFeedResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Featured product feed search"
     *          ],
     *          "parameters": [
     *              {
     *                  "name": "accept-language",
     *                  "in": "header",
     *                  "description": "Two letter language code"
     *              },
     *              {
     *                  "name": "X-Spryker-Api-Key",
     *                  "in": "header",
     *                  "required": true,
     *                  "description": "API key for authentication"
     *              }
     *          ],
     *          "isIdNullable": true
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->getFactory()
            ->createCatalogSearchReader()
            ->findFeaturedRegularProducts($restRequest);
    }
}
