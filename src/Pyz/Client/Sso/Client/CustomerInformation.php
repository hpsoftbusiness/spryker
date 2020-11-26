<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Client\Sso\SsoConfig;

class CustomerInformation implements CustomerInformationInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var \Pyz\Client\Sso\SsoConfig
     */
    protected $ssoConfig;

    /**
     * @param \GuzzleHttp\ClientInterface $client
     * @param \Pyz\Client\Sso\SsoConfig $ssoConfig
     */
    public function __construct(ClientInterface $client, SsoConfig $ssoConfig)
    {
        $this->httpClient = $client;
        $this->ssoConfig = $ssoConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomerInformationBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer): ?CustomerTransfer
    {
        $requestParams = $this->geRequestParams($ssoAccessTokenTransfer);
        $result = $this->httpClient->request(
            'GET',
            $this->ssoConfig->getCustomerInformationUrl(),
            $requestParams
        );

        $result = json_decode($result->getBody()->getContents(), true);

        return (new CustomerTransfer())
            ->setEmail($result['Data']['Email'])
            ->setMyWorldCustomerId($result['Data']['CustomerID'])
            ->setMyWorldCustomerNumber($result['Data']['CustomerNumber'])
            ->setFirstName($result['Data']['Firstname'])
            ->setLastName($result['Data']['Lastname'])
            ->setDateOfBirth($result['Data']['BirthdayDate'])
            ->setPhone($result['Data']['MobilePhoneNumber'])
            ->setIsActive($result['Data']['Status'] === 'Active')
            ->setCustomerType($result['Data']['CustomerType'] - 1);
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
