<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Pyz\Glue\WeclappRestApi\WeclappRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidator as SprykerHttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpRequestValidator extends SprykerHttpRequestValidator
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    protected function validateRequiredHeaders(Request $request): ?RestErrorMessageTransfer
    {
        $headerData = $request->headers->all();

        $restErrorMessageTransfer = $this->validateAccessControlRequestMethod($request);
        if ($restErrorMessageTransfer) {
            return $restErrorMessageTransfer;
        }

        $restErrorMessageTransfer = $this->validateAccessControlRequestHeader($request);
        if ($restErrorMessageTransfer) {
            return $restErrorMessageTransfer;
        }

        // for Weclapp webhook it must not be checked
        if (!isset($headerData[RequestConstantsInterface::HEADER_ACCEPT])
            && $request->get(RestResourceInterface::RESOURCE_TYPE) !== WeclappRestApiConfig::RESOURCE_WEBHOOK
        ) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Not acceptable.')
                ->setStatus(Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!isset($headerData[RequestConstantsInterface::HEADER_CONTENT_TYPE])) {
            return (new RestErrorMessageTransfer())
                ->setDetail('Unsupported media type.')
                ->setStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        return null;
    }
}
