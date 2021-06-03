<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi;

use Generated\Shared\Transfer\AccessTokenTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiFactory getFactory()
 */
class MyWorldMarketplaceApiClient extends AbstractClient implements MyWorldMarketplaceApiClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\AccessTokenTransfer
     */
    public function getAccessToken(): AccessTokenTransfer
    {
        return $this->getFactory()->createAccessToken()->getAccessToken();
    }

    /**
     * @param string $url
     * @param array $requestParams
     * @param string $requestMethod
     *
     * @return \Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer
     */
    public function performApiRequest(
        string $url,
        array $requestParams = [],
        string $requestMethod = 'POST'
    ): MyWorldMarketplaceApiResponseTransfer {
        return $this->getFactory()->createRequest()->request($url, $requestParams, $requestMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerInformationByCustomerNumberOrId(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()->createCustomerInformationByCustomerNumber()->getCustomerInformationByCustomerNumber(
            $customerTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $currencyCode
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[]
     */
    public function getCustomerBalanceByCurrency(
        CustomerTransfer $customerTransfer,
        ?string $currencyCode = null
    ): array {
        return $this->getFactory()->createCustomerBalanceRequestHandler()->getCustomerBalancesByCurrency(
            $customerTransfer,
            $currencyCode
        );
    }
}
