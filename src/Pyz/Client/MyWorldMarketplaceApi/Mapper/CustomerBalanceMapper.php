<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Mapper;

use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;

class CustomerBalanceMapper implements CustomerBalanceMapperInterface
{
    private const KEY_DATA_PAYMENT_OPTION_ID = 'PaymentOptionID';
    private const KEY_DATA_PAYMENT_OPTION_NAME = 'PaymentOptionName';
    private const KEY_DATA_CURRENCY_ID = 'CurrencyID';
    private const KEY_DATA_AVAILABLE_BALANCE = 'AvailableBalance';
    private const KEY_DATA_TARGET_CURRENCY_ID = 'TargetCurrencyID';
    private const KEY_DATA_TARGET_AVAILABLE_BALANCE = 'TargetAvailableBalance';
    private const KEY_DATA_EXCHANGE_RATE = 'ExchangeRate';

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[]
     */
    public function mapCustomerBalanceByCurrencyData(array $data): array
    {
        return array_map(static function (array $balanceData): CustomerBalanceByCurrencyTransfer {
            return (new CustomerBalanceByCurrencyTransfer())
                ->setPaymentOptionId($balanceData[self::KEY_DATA_PAYMENT_OPTION_ID] ?? null)
                ->setPaymentOptionName($balanceData[self::KEY_DATA_PAYMENT_OPTION_NAME] ?? null)
                ->setCurrencyID($balanceData[self::KEY_DATA_CURRENCY_ID] ?? null)
                ->setAvailableBalance($balanceData[self::KEY_DATA_AVAILABLE_BALANCE] ?? null)
                ->setTargetCurrencyID($balanceData[self::KEY_DATA_TARGET_CURRENCY_ID] ?? null)
                ->setTargetAvailableBalance($balanceData[self::KEY_DATA_TARGET_AVAILABLE_BALANCE] ?? null)
                ->setExchangeRate($balanceData[self::KEY_DATA_EXCHANGE_RATE] ?? null);
        }, $data);
    }
}
