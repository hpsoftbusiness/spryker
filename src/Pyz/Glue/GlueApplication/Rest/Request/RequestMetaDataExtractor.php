<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication\Rest\Request;

use Pyz\Glue\GlueApplication\Rest\Serialize\DecoderMatcher;
use Pyz\Glue\WeclappRestApi\WeclappRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractor as SprykerRequestMetaDataExtractor;
use Symfony\Component\HttpFoundation\Request;

class RequestMetaDataExtractor extends SprykerRequestMetaDataExtractor
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function findContentTypeFormat(Request $request): string
    {
        // for Weclapp webhook it must be hardcoded
        if ($request->get(RestResourceInterface::RESOURCE_TYPE) === WeclappRestApiConfig::RESOURCE_WEBHOOK) {
            return DecoderMatcher::WECLAPP_WEBHOOK_FORMAT;
        }

        return parent::findContentTypeFormat($request);
    }
}
