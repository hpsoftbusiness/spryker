<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi;

use Generated\Shared\Transfer\AccessTokenTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;

interface MyWorldMarketplaceApiClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\AccessTokenTransfer
     */
    public function getAccessToken(): AccessTokenTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerInformationByCustomerNumberOrId(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * @param string $url
     * @param array $requestParams
     * @param string $requestMethod
     *
     * @return \Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer
     */
    public function performApiRequest(string $url, array $requestParams = [], string $requestMethod = 'POST'): MyWorldMarketplaceApiResponseTransfer;

    /**
     * Specification:
     * - Requests customer balance data from MyWorld marketing API.
     * - Returns collection of balances.
     * - If currency code is not provided falls back to store currency code.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $currencyCode
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[]
     */
    public function getCustomerBalanceByCurrency(CustomerTransfer $customerTransfer, ?string $currencyCode = null): array;
}
