<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\Request;

use Exception;
use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper\ResponseMapperInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator\ResponseValidatorInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

class Request implements RequestInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator\ResponseValidatorInterface
     */
    protected $responseValidator;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper\ResponseMapperInterface
     */
    protected $responseMapper;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected $errorLogger;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator\ResponseValidatorInterface $responseValidator
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper\ResponseMapperInterface $responseMapper
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     */
    public function __construct(
        ClientInterface $httpClient,
        UtilEncodingServiceInterface $utilEncodingService,
        ResponseValidatorInterface $responseValidator,
        ResponseMapperInterface $responseMapper,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig,
        ErrorLoggerInterface $errorLogger
    ) {
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->responseValidator = $responseValidator;
        $this->responseMapper = $responseMapper;
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
        $this->errorLogger = $errorLogger;
    }

    /**
     * @param string $url
     * @param array $requestParams
     * @param string $requestMethod
     *
     * @return \Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer
     */
    public function request(string $url, array $requestParams = [], string $requestMethod = 'POST'): MyWorldMarketplaceApiResponseTransfer
    {
        try {
            $requestParams = $this->prepareRequestParams($requestParams);

            $response = $this->httpClient->request(
                $requestMethod,
                $url,
                $requestParams
            );

            $responseData = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);
            $isValid = $this->responseValidator->validate($responseData);

            $myWorldMarketplaceApiResponseTransfer = $this->responseMapper->map($responseData);
            $myWorldMarketplaceApiResponseTransfer->setIsSuccess($isValid);

            return $myWorldMarketplaceApiResponseTransfer;
        } catch (Exception $exception) {
            $this->errorLogger->log($exception);
        }

        return (new MyWorldMarketplaceApiResponseTransfer())->setIsSuccess(false);
    }

    /**
     * @param array $requestParams
     *
     * @return array
     */
    protected function prepareRequestParams(array $requestParams): array
    {
        $requestParams['headers']['User-Agent'] = $this->myWorldMarketplaceApiConfig->getUserAgent();

        return $requestParams;
    }
}
