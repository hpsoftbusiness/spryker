<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiServiceInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\ConfirmPaymentAdapter;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\CreatePaymentSessionAdapter;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\CreateRefundAdapter;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\GenerateSmsCodeAdapter;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\GetPaymentAdapter;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\ValidateSmsCodeAdapter;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\ConfirmPaymentConverter;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\CreatePaymentSessionConverter;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\CreateRefundConverter;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\GenerateSmsCodeConverter;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\GetPaymentConverter;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\ValidateSmsCodeConverter;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\ConfirmPaymentMapper;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\CreatePaymentSessionMapper;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\CreateRefundMapper;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\GenerateSmsCodeMapper;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\GetPaymentMapper;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\ValidateSmsCodeMapper;
use Pyz\Zed\MyWorldPaymentApi\Business\Request\MyWorldPaymentApiRequest;
use Pyz\Zed\MyWorldPaymentApi\Business\Request\MyWorldPaymentApiRequestInterface;
use Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiDependencyProvider;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig getConfig()
 */
class MyWorldPaymentApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentApiDependencyProvider::UTIL_ENCODING_SERVICE);
    }

    /**
     * @return \Pyz\Service\MyWorldPaymentApi\MyWorldPaymentApiServiceInterface
     */
    public function getMyWorldPaymentApiService(): MyWorldPaymentApiServiceInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentApiDependencyProvider::MY_WORLD_PAYMENT_API_SERVICE);
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentApiDependencyProvider::GUZZLE_CLIENT);
    }

    /**
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface $adapter
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface $converter
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface $mapper
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Request\MyWorldPaymentApiRequestInterface
     */
    public function createMyWorldPaymentApiRequest(
        MyWorldPaymentApiAdapterInterface $adapter,
        MyWorldPaymentApiConverterInterface $converter,
        MyWorldPaymentApiMapperInterface $mapper
    ): MyWorldPaymentApiRequestInterface {
        $config = $this->getConfig();

        return new MyWorldPaymentApiRequest($adapter, $converter, $mapper, $config);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\GenerateSmsCodeAdapter
     */
    public function createGenerateSmsCodeAdapter(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): GenerateSmsCodeAdapter
    {
        return new GenerateSmsCodeAdapter(
            $this->getClient(),
            $this->getUtilEncodingService(),
            $this->getMyWorldPaymentApiService(),
            $this->getConfig(),
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    public function createGenerateSmsCodeConverter(): MyWorldPaymentApiConverterInterface
    {
        return new GenerateSmsCodeConverter($this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    public function createGenerateSmsCodeMapper(): MyWorldPaymentApiMapperInterface
    {
        return new GenerateSmsCodeMapper();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\ValidateSmsCodeAdapter
     */
    public function createValidateSmsCodeAdapter(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): ValidateSmsCodeAdapter
    {
        return new ValidateSmsCodeAdapter(
            $this->getClient(),
            $this->getUtilEncodingService(),
            $this->getMyWorldPaymentApiService(),
            $this->getConfig(),
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    public function createValidateSmsCodeConverter(): MyWorldPaymentApiConverterInterface
    {
        return new ValidateSmsCodeConverter($this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    public function createValidateSmsCodeMapper(): MyWorldPaymentApiMapperInterface
    {
        return new ValidateSmsCodeMapper();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface
     */
    public function createPaymentSessionAdapter(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldPaymentApiAdapterInterface
    {
        return new CreatePaymentSessionAdapter(
            $this->getClient(),
            $this->getUtilEncodingService(),
            $this->getMyWorldPaymentApiService(),
            $this->getConfig(),
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    public function createPaymentSessionConverter(): MyWorldPaymentApiConverterInterface
    {
        return new CreatePaymentSessionConverter($this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    public function createPaymentSessionMapper(): MyWorldPaymentApiMapperInterface
    {
        return new CreatePaymentSessionMapper();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface
     */
    public function createPaymentSessionApiCallAdapter(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldPaymentApiAdapterInterface
    {
        return new ConfirmPaymentAdapter(
            $this->getClient(),
            $this->getUtilEncodingService(),
            $this->getMyWorldPaymentApiService(),
            $this->getConfig(),
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    public function createPaymentSessionApiCallConverter(): MyWorldPaymentApiConverterInterface
    {
        return new ConfirmPaymentConverter($this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    public function createPaymentSessionApiCallMapper(): MyWorldPaymentApiMapperInterface
    {
        return new ConfirmPaymentMapper();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface
     */
    public function createGetPaymentAdapter(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldPaymentApiAdapterInterface
    {
        return new GetPaymentAdapter(
            $this->getClient(),
            $this->getUtilEncodingService(),
            $this->getMyWorldPaymentApiService(),
            $this->getConfig(),
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    public function createGetPaymentConverter(): MyWorldPaymentApiConverterInterface
    {
        return new GetPaymentConverter($this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    public function createGetPaymentMapper(): MyWorldPaymentApiMapperInterface
    {
        return new GetPaymentMapper();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface
     */
    public function createCreateRefundAdapter(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldPaymentApiAdapterInterface
    {
        return new CreateRefundAdapter(
            $this->getClient(),
            $this->getUtilEncodingService(),
            $this->getMyWorldPaymentApiService(),
            $this->getConfig(),
            $myWorldApiRequestTransfer
        );
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    public function createCreateRefundConverter(): MyWorldPaymentApiConverterInterface
    {
        return new CreateRefundConverter($this->getUtilEncodingService());
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    public function createCreateRefundMapper(): MyWorldPaymentApiMapperInterface
    {
        return new CreateRefundMapper();
    }
}
