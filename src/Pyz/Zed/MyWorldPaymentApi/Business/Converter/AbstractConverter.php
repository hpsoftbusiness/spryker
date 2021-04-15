<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Converter;

use Generated\Shared\Transfer\MyWorldApiErrorResponseTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Psr\Http\Message\ResponseInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

abstract class AbstractConverter implements MyWorldPaymentApiConverterInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiResponseTransfer $responseTransfer
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    abstract public function updateResponseTransfer(MyWorldApiResponseTransfer $responseTransfer, array $response): MyWorldApiResponseTransfer;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(UtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function convertToResponseTransfer(ResponseInterface $response, $isSuccess = true): MyWorldApiResponseTransfer
    {
        $responseBody = $this->utilEncodingService->decodeJson($response->getBody(), true);
        $responseTransfer = new MyWorldApiResponseTransfer();
        $responseTransfer->setIsSuccess($isSuccess);

        if ($isSuccess) {
            return $this->updateResponseTransfer($responseTransfer, $responseBody);
        }

        $error = new MyWorldApiErrorResponseTransfer();
        $error->setErrorCode($responseBody['ResultCode'] ?? null);
        $error->setErrorMessage($responseBody['ResultDescription'] ?? null);
        $error->setStatusCode($response->getStatusCode());

        $responseTransfer->setError($error);

        return $responseTransfer;
    }
}
