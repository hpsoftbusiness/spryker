<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Adapter;

use DateTime;
use DateTimeInterface;
use Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiServiceInterface;
use Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

abstract class AbstractAdapter implements MyWorldPaymentApiAdapterInterface
{
    protected const HTTP_METHOD_POST = 'POST';
    protected const HTTP_METHOD_GET = 'GET';

    protected const HEADER_MWS_IDENTITY_TOKEN_KEY = 'MWS-Identity-Token';
    protected const HEADER_AUTHORIZATION_KEY = 'Authorization';
    protected const HEADER_DATE_KEY = 'Date';
    protected const HEADER_X_MWS_API_VERSION_KEY = 'X-MWS-ApiVersion';
    protected const HEADER_X_MWS_API_VERSION_VALUE = '1.0';
    protected const HEADER_CONTENT_TYPE_KEY = 'Content-Type';
    protected const HEADER_CONTENT_TYPE_VALUE = 'application/json';

    protected const TEST_HEADER_X_MWS_IDENTITY_TOKEN_KEY_TYPE = 'MWS-Identity-Token-KeyType';
    protected const TEST_HEADER_X_MWS_IDENTITY_TOKEN_KEY_TYPE_VALUE = 'HmacSha256';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiServiceInterface
     */
    protected $myWorldPaymentApiService;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\MyWorldApiRequestTransfer|null
     */
    protected $myWorldApiRequestTransfer;

    /**
     * @var false
     */
    private $isSubToken = false;

    /**
     * @return string
     */
    abstract public function getUri(): string;

    /**
     * @return string
     */
    abstract public function getHttpMethod(): string;

    /**
     * @param \GuzzleHttp\ClientInterface $client
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiServiceInterface $myWorldPaymentApiService
     * @param \Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig $config
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer|null $myWorldApiRequestTransfer
     */
    public function __construct(
        ClientInterface $client,
        UtilEncodingServiceInterface $utilEncodingService,
        MyWorldPaymentApiServiceInterface $myWorldPaymentApiService,
        MyWorldPaymentApiConfig $config,
        ?MyWorldApiRequestTransfer $myWorldApiRequestTransfer = null
    ) {
        $this->client = $client;
        $this->utilEncodingService = $utilEncodingService;
        $this->myWorldPaymentApiService = $myWorldPaymentApiService;
        $this->config = $config;
        $this->myWorldApiRequestTransfer = $myWorldApiRequestTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendRequest(array $data): ResponseInterface
    {
        $options[RequestOptions::BODY] = $this->utilEncodingService->encodeJson($data);

        $options = $this->prepareHeaders($options);
        $options = $this->prepareAuthorizationHeader($options);

        return $this->send($options);
    }

    /**
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function send(array $options): ResponseInterface
    {
        return $this->client->request(
            $this->getHttpMethod(),
            $this->getUri(),
            $options
        );
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function prepareHeaders(array $options): array
    {
        $options[RequestOptions::HEADERS][static::HEADER_CONTENT_TYPE_KEY] = static::HEADER_CONTENT_TYPE_VALUE;
        $options[RequestOptions::HEADERS][static::HEADER_X_MWS_API_VERSION_KEY] = static::HEADER_X_MWS_API_VERSION_VALUE;
        $options[RequestOptions::HEADERS][static::HEADER_DATE_KEY] = (new DateTime())->format(
            DateTimeInterface::RFC7231
        );

        if ($this->isSubToken) {
            $options[RequestOptions::HEADERS][static::TEST_HEADER_X_MWS_IDENTITY_TOKEN_KEY_TYPE] =
                static::TEST_HEADER_X_MWS_IDENTITY_TOKEN_KEY_TYPE_VALUE;
        }

        return $options;
    }

    /**
     * @return string
     */
    protected function getMwsIdentityToken(): string
    {
        $this->myWorldApiRequestTransfer->requirePaymentSessionRequest();
        $paymentSessionRequestTransfer = $this->myWorldApiRequestTransfer->getPaymentSessionRequest();
        $paymentSessionRequestTransfer->requireSsoAccessToken();

        return $paymentSessionRequestTransfer->getSsoAccessToken()->getAccessToken();
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function prepareAuthorizationHeader(array $options): array
    {
        $authorizationHeaderRequest = new AuthorizationHeaderRequestTransfer();
        $authorizationHeaderRequest->setUri($this->getUri())
            ->setHttpMethod($this->getHttpMethod())
            ->setBody($options[RequestOptions::BODY])
            ->setHeaders([RequestOptions::HEADERS])
            ->setApiKeyId($this->config->getApiKeyId())
            ->setSecretApiKey($this->config->getSecretApiKey())
            ->setContentType(static::HEADER_CONTENT_TYPE_VALUE);

        $options[RequestOptions::HEADERS][static::HEADER_AUTHORIZATION_KEY] = $this->myWorldPaymentApiService->getAuthorizationHeader(
            $authorizationHeaderRequest
        );

        return $options;
    }

    /**
     * @return $this
     */
    public function allowUsingStubToken():AbstractAdapter
    {
        $this->isSubToken = true;

        return $this;
    }
}
