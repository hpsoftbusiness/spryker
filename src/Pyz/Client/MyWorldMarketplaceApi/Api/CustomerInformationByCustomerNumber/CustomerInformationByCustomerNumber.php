<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\CustomerInformationByCustomerNumber;

use Exception;
use Generated\Shared\Transfer\CustomerInformationByCustomerNumberRequestTransfer;
use Generated\Shared\Transfer\CustomerInformationByCustomerNumberResponseTransfer;
use Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;

class CustomerInformationByCustomerNumber implements CustomerInformationByCustomerNumberInterface
{
    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\Api\Request\RequestInterface $request
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     */
    public function __construct(
        RequestInterface $request,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
    ) {
        $this->request = $request;
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerInformationByCustomerNumberResponseTransfer
     */
    public function getCustomerInformationByCustomerNumber(CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer): CustomerInformationByCustomerNumberResponseTransfer
    {
        $myWorldMarketplaceApiResponseTransfer = $this->request->request(
            $this->buildRequestUrl($customerInformationByCustomerNumberRequestTransfer),
            $this->getRequestParams($customerInformationByCustomerNumberRequestTransfer),
            'GET'
        );

        if ($myWorldMarketplaceApiResponseTransfer->getIsSuccess()) {
            //magic
        }
    }

    protected function buildRequestUrl(CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer): string
    {
        $customerId = $customerInformationByCustomerNumberRequestTransfer->getMyWorldCustomerId() ?? $customerInformationByCustomerNumberRequestTransfer->getMyWorldCustomerNumber();
        if (!$customerId) {
            throw new Exception('Customer ID or number required.');
        }

        return sprintf('%s/customers/%s', $this->myWorldMarketplaceApiConfig->getApiUrl(), $customerId);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer
     *
     * @return array[]
     */
    protected function getRequestParams(CustomerInformationByCustomerNumberRequestTransfer $customerInformationByCustomerNumberRequestTransfer): array
    {
        $customerInformationByCustomerNumberRequestTransfer->requireAccessToken();

        return [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $customerInformationByCustomerNumberRequestTransfer->getAccessToken()),
            ],
        ];
    }
}
