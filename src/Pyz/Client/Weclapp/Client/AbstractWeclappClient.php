<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client;

use Generated\Shared\Transfer\ApiOutboundLogTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Pyz\Client\ApiLog\ApiLogClientInterface;
use Pyz\Client\Weclapp\WeclappConfig;
use Pyz\Shared\ContentHeaders\ContentHeadersConfig;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

abstract class AbstractWeclappClient
{
    protected const APP_NAME = 'Weclapp';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Pyz\Client\Weclapp\WeclappConfig
     */
    protected $weclappConfig;

    /**
     * @var \Pyz\Client\ApiLog\ApiLogClientInterface
     */
    protected $apiLogClient;

    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected $errorLogger;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Pyz\Client\Weclapp\WeclappConfig $weclappConfig
     * @param \Pyz\Client\ApiLog\ApiLogClientInterface $apiLogClient
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     */
    public function __construct(
        ClientInterface $httpClient,
        WeclappConfig $weclappConfig,
        ApiLogClientInterface $apiLogClient,
        ErrorLoggerInterface $errorLogger
    ) {
        $this->httpClient = $httpClient;
        $this->weclappConfig = $weclappConfig;
        $this->apiLogClient = $apiLogClient;
        $this->errorLogger = $errorLogger;
    }

    /**
     * @param string $requestMethod
     * @param string $actionUrl
     * @param array|null $requestBody
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    protected function callWeclapp(
        string $requestMethod,
        string $actionUrl,
        ?array $requestBody = null
    ): ?ResponseInterface {
        $url = $this->weclappConfig->getApiUrl() . $actionUrl;
        if (is_array($requestBody)) {
            try {
                $body = json_encode($requestBody, JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                $this->errorLogger->log($exception);
                throw $exception;
            }
        } else {
            $body = null;
        }
        $startTimestamp = microtime(true);

        try {
            $response = $this->httpClient->request(
                $requestMethod,
                $this->weclappConfig->getApiUrl() . $actionUrl,
                [
                    'headers' => $this->getRequestHeaders(),
                    'body' => $body,
                ]
            );
            $this->createApiOutboundLog($requestMethod, $url, $body, $startTimestamp, $response);

            return $response;
        } catch (GuzzleException $exception) {
            $this->errorLogger->log($exception);
            $response = method_exists($exception, 'getResponse') ? $exception->getResponse() : null;
            $this->createApiOutboundLog($requestMethod, $url, $body, $startTimestamp, $response);
            throw $exception;
        }
    }

    /**
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        return [
            'AuthenticationToken' => $this->weclappConfig->getApiToken(),
            ContentHeadersConfig::CONTENT_TYPE_LABEL => ContentHeadersConfig::CONTENT_TYPE_JSON,
        ];
    }

    /**
     * @param string $method
     * @param string $url
     * @param string|null $requestBody
     * @param float $startTimestamp
     * @param \Psr\Http\Message\ResponseInterface|null $response
     *
     * @return void
     */
    protected function createApiOutboundLog(
        string $method,
        string $url,
        ?string $requestBody,
        float $startTimestamp,
        ?ResponseInterface $response
    ): void {
        $apiOutboundLogTransfer = new ApiOutboundLogTransfer();
        $apiOutboundLogTransfer->setMethod($method)
            ->setUrl($url)
            ->setRequestHeaders(json_encode($this->getRequestHeaders()))
            ->setRequestBody($requestBody)
            ->setResponseCode($response ? $response->getStatusCode() : null)
            ->setResponseBody($response ? $response->getBody()->__toString() : null)
            ->setAppName(static::APP_NAME)
            ->setTime((int)((microtime(true) - $startTimestamp) * 1000));

        $this->apiLogClient->createApiOutboundLogViaQueue($apiOutboundLogTransfer);
    }
}
