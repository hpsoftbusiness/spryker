<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\CustomerBalance;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerBalanceRequestHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string|null $currencyCode
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[]
     */
    public function getCustomerBalancesByCurrency(CustomerTransfer $customerTransfer, ?string $currencyCode = null): array;
}
