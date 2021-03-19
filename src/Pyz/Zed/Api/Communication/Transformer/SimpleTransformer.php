<?php

namespace Pyz\Zed\Api\Communication\Transformer;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Communication\Transformer\Transformer;
use Symfony\Component\HttpFoundation\Response;

class SimpleTransformer extends Transformer
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addResponseContent(
        ApiRequestTransfer $apiRequestTransfer,
        ApiResponseTransfer $apiResponseTransfer,
        Response $response
    ) {
        if ($this->isContentless($apiResponseTransfer)) {
            return $response;
        }

        $content = $apiResponseTransfer->getData();

        if ($this->apiConfig->isApiDebugEnabled()) {
            $content['_stackTrace'] = $apiResponseTransfer->getStackTrace();
            $content['_request'] = $apiRequestTransfer->toArray();
        }

        $content = $this->formatter->format($content);
        $response->setContent($content);

        return $response;
    }
}
