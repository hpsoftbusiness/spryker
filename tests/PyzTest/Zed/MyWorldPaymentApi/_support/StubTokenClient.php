<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPaymentApi;

use DateTime;
use DateTimeInterface;
use Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiService;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Throwable;

class StubTokenClient
{
    private const BASE_URL = 'https://preprod-payments-api.myworldwebservices.com';
    private const STUB_TOKEN_URI = '/stub/sso/auth/e2ae13e5-fae6-4a84-86ef-a7fe00eb6b3d';
    private const HEADER_METHOD = 'POST';
    private const HEADER_AUTHORIZATION_KEY = 'Authorization';
    private const HEADER_CONTENT_TYPE_KEY = 'Content-Type';
    private const HEADER_CONTENT_TYPE_VALUE = 'application/json';
    private const BODY = '{}';
    private const TOKEN_KEY = 'Token';
    private const API_KEY = 'spryker_at_dev';
    private const SECRET_API_KEY = 'spryker_at_dev';
    private const HEADER_DATE_KEY = 'Date';
    private const HEADER_X_MWS_API_VERSION_KEY = 'X-MWS-ApiVersion';
    private const HEADER_X_MWS_API_VERSION_VALUE = '1.0';

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var \Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiService
     */
    private $myWorldPaymentApiService;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    private $utilEncodingService;
    
    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::BASE_URL]);
        $this->myWorldPaymentApiService = new MyWorldPaymentApiService();
        $this->utilEncodingService = new UtilEncodingService();
    }

    /**
     * @return string
     */
    public function getStubToken(): string
    {
        $authorizationHeaderRequest = new AuthorizationHeaderRequestTransfer();
        $authorizationHeaderRequest->setBody(self::BODY);
        $authorizationHeaderRequest->setUri(self::BASE_URL . self::STUB_TOKEN_URI);
        $authorizationHeaderRequest->setHttpMethod(self::HEADER_METHOD);
        $authorizationHeaderRequest->setContentType(self::HEADER_CONTENT_TYPE_VALUE);
        $authorizationHeaderRequest->setApiKeyId(self::API_KEY);
        $authorizationHeaderRequest->setSecretApiKey(self::SECRET_API_KEY);

        $options[RequestOptions::BODY] = self::BODY;
        $options[RequestOptions::HEADERS][static::HEADER_CONTENT_TYPE_KEY] = static::HEADER_CONTENT_TYPE_VALUE;
        $options[RequestOptions::HEADERS][static::HEADER_X_MWS_API_VERSION_KEY] = static::HEADER_X_MWS_API_VERSION_VALUE;
        $options[RequestOptions::HEADERS][static::HEADER_DATE_KEY] = (new DateTime())->format(DateTimeInterface::RFC7231);
        $options[RequestOptions::HEADERS][static::HEADER_AUTHORIZATION_KEY] = $this->myWorldPaymentApiService->getAuthorizationHeader(
            $authorizationHeaderRequest
        );

        try {
            $response = $this->client->request(self::HEADER_METHOD, self::STUB_TOKEN_URI, $options);
            $responseBody = $this->utilEncodingService->decodeJson($response->getBody(), true);

            return $responseBody[self::TOKEN_KEY];
        } catch (Throwable $requestException) {
            return '';
        }
    }
}
