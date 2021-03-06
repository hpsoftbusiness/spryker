<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi;

use GuzzleHttp\ClientInterface;
use Pyz\Client\Currency\CurrencyClientInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessToken;
use Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\CustomerBalance\CustomerBalanceRequestHandler;
use Pyz\Client\MyWorldMarketplaceApi\Api\CustomerBalance\CustomerBalanceRequestHandlerInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber\CustomerInformationByCustomerNumber;
use Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber\CustomerInformationByCustomerNumberInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\Request\Request;
use Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper\ResponseMapper;
use Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper\ResponseMapperInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator\ResponseValidator;
use Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator\ResponseValidatorInterface;
use Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerBalanceMapper;
use Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerBalanceMapperInterface;
use Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerInformationMapper;
use Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerInformationMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

/**
 * @method \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 */
class MyWorldMarketplaceApiFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface
     */
    public function createAccessToken(): AccessTokenInterface
    {
        return new AccessToken(
            $this->getHttpClient(),
            $this->getConfig(),
            $this->getUtilEncodingService(),
            $this->getErrorLogger()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber\CustomerInformationByCustomerNumberInterface
     */
    public function createCustomerInformationByCustomerNumber(): CustomerInformationByCustomerNumberInterface
    {
        return new CustomerInformationByCustomerNumber(
            $this->createRequest(),
            $this->createAccessToken(),
            $this->createCustomerInformationMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Api\ResponseValidator\ResponseValidatorInterface
     */
    public function createResponseValidator(): ResponseValidatorInterface
    {
        return new ResponseValidator();
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper\ResponseMapperInterface
     */
    public function createResponseMapper(): ResponseMapperInterface
    {
        return new ResponseMapper();
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    public function getErrorLogger(): ErrorLoggerInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::ERROR_LOGGER);
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerInformationMapperInterface
     */
    public function createCustomerInformationMapper(): CustomerInformationMapperInterface
    {
        return new CustomerInformationMapper();
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface
     */
    public function createRequest(): RequestInterface
    {
        return new Request(
            $this->getHttpClient(),
            $this->getUtilEncodingService(),
            $this->createResponseValidator(),
            $this->createResponseMapper(),
            $this->getConfig(),
            $this->getErrorLogger()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Api\CustomerBalance\CustomerBalanceRequestHandlerInterface
     */
    public function createCustomerBalanceRequestHandler(): CustomerBalanceRequestHandlerInterface
    {
        return new CustomerBalanceRequestHandler(
            $this->createRequest(),
            $this->createAccessToken(),
            $this->getCurrencyClient(),
            $this->createCustomerBalanceMapper(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\Mapper\CustomerBalanceMapperInterface
     */
    public function createCustomerBalanceMapper(): CustomerBalanceMapperInterface
    {
        return new CustomerBalanceMapper();
    }

    /**
     * @return \Pyz\Client\Currency\CurrencyClientInterface
     */
    public function getCurrencyClient(): CurrencyClientInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::CLIENT_CURRENCY);
    }
}
