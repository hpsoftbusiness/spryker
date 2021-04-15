<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Request;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use GuzzleHttp\Exception\RequestException;
use Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface;
use Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface;
use Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig;

class MyWorldPaymentApiRequest implements MyWorldPaymentApiRequestInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface
     */
    protected $adapter;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface
     */
    protected $converter;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface
     */
    protected $mapper;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig
     */
    protected $config;

    /**
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\Adapter\MyWorldPaymentApiAdapterInterface $adapter
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\Converter\MyWorldPaymentApiConverterInterface $converter
     * @param \Pyz\Zed\MyWorldPaymentApi\Business\Mapper\MyWorldPaymentApiMapperInterface $mapper
     * @param \Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig $config
     */
    public function __construct(
        MyWorldPaymentApiAdapterInterface $adapter,
        MyWorldPaymentApiConverterInterface $converter,
        MyWorldPaymentApiMapperInterface $mapper,
        MyWorldPaymentApiConfig $config
    ) {
        $this->adapter = $adapter;
        $this->converter = $converter;
        $this->mapper = $mapper;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function request(MyWorldApiRequestTransfer $requestTransfer): MyWorldApiResponseTransfer
    {
        $requestData = $this->mapper->buildRequestArray($requestTransfer);
        $isSuccess = true;

        try {
            $response = $this->adapter->sendRequest($requestData);
        } catch (RequestException $requestException) {
            $response = $requestException->getResponse();
            $isSuccess = false;
        }

        return $this->converter->convertToResponseTransfer($response, $isSuccess);
    }
}
