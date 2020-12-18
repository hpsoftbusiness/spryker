<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber;

use Exception;
use Generated\Shared\Transfer\CustomerInformationByCustomerNumberRequestTransfer;
use Generated\Shared\Transfer\CustomerInformationByCustomerNumberResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface;
use Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;

class CustomerInformationByCustomerNumber implements CustomerInformationByCustomerNumberInterface
{
    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface
     */
    protected $accessToken;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface $request
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken\AccessTokenInterface $accessToken
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     */
    public function __construct(
        RequestInterface $request,
        AccessTokenInterface $accessToken,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
    ) {
        $this->request = $request;
        $this->accessToken = $accessToken;
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerInformationByCustomerNumber(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $myWorldMarketplaceApiResponseTransfer = $this->request->request(
            $this->buildRequestUrl($customerTransfer),
            $this->getRequestParams($this->accessToken->getAccessToken()->getAccessToken()),
            'GET'
        );

        if ($myWorldMarketplaceApiResponseTransfer->getIsSuccess()) {
            //magic
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     * @throws \Exception
     */
    protected function buildRequestUrl(CustomerTransfer $customerTransfer): string
    {
        $customerId = $customerTransfer->getMyWorldCustomerId() ?? $customerTransfer->getMyWorldCustomerNumber();
        if (!$customerId) {
            throw new Exception('Customer ID or number required.');
        }

        return sprintf('%s/customers/%s', $this->myWorldMarketplaceApiConfig->getApiUrl(), $customerId);
    }

    /**
     * @param string $accessToken
     *
     * @return array[]
     */
    protected function getRequestParams(string $accessToken): array
    {
        return [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $accessToken),
            ],
        ];
    }
}
