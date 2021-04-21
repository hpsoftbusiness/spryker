<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Mapper;

interface CustomerBalanceMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[]
     */
    public function mapCustomerBalanceByCurrencyData(array $data): array;
}
