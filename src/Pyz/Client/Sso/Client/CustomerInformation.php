<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client;

use Exception;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface;
use Pyz\Client\Sso\Client\Validator\CustomerInformationValidatorInterface;
use Pyz\Client\Sso\SsoConfig;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

class CustomerInformation implements CustomerInformationInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Pyz\Client\Sso\Client\Validator\CustomerInformationValidatorInterface
     */
    protected $customerInformationValidator;

    /**
     * @var \Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface
     */
    protected $customerInformationMapper;

    /**
     * @var \Pyz\Client\Sso\SsoConfig
     */
    protected $ssoConfig;

    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected $errorLogger;

    /**
     * @param \GuzzleHttp\ClientInterface $client
     * @param \Pyz\Client\Sso\Client\Validator\CustomerInformationValidatorInterface $customerInformationValidator
     * @param \Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface $customerInformationMapper
     * @param \Pyz\Client\Sso\SsoConfig $ssoConfig
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     */
    public function __construct(
        ClientInterface $client,
        CustomerInformationValidatorInterface $customerInformationValidator,
        CustomerInformationMapperInterface $customerInformationMapper,
        SsoConfig $ssoConfig,
        ErrorLoggerInterface $errorLogger
    ) {
        $this->httpClient = $client;
        $this->customerInformationValidator = $customerInformationValidator;
        $this->customerInformationMapper = $customerInformationMapper;
        $this->ssoConfig = $ssoConfig;
        $this->errorLogger = $errorLogger;
    }

    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerInformationBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer): CustomerTransfer
    {
        $requestParams = $this->geRequestParams($ssoAccessTokenTransfer);
        try {
            $result = $this->httpClient->request(
                'GET',
                $this->ssoConfig->getCustomerInformationUrl(),
                $requestParams
            );
        } catch (Exception $e) {
            $this->errorLogger->log($e);

            return new CustomerTransfer();
        }

        $result = json_decode($result->getBody()->getContents(), true);

        if ($this->customerInformationValidator->isValid($result)) {
            return $this->customerInformationMapper->mapDataToCustomerTransfer($result);
        }

        return new CustomerTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return array[]
     */
    protected function geRequestParams(SsoAccessTokenTransfer $ssoAccessTokenTransfer): array
    {
        return [
            'headers' => [
                'User-Agent' => $this->ssoConfig->getUserAgent(),
                'Authorization' => sprintf('Bearer %s', $ssoAccessTokenTransfer->getIdToken()),
            ],

        ];
    }
}
