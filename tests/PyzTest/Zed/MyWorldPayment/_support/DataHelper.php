<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment;

use ArrayObject;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\DataBuilder\CustomerBalanceBuilder;
use Generated\Shared\DataBuilder\CustomerBalanceByCurrencyBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\MyWorldApiRequestBuilder;
use Generated\Shared\DataBuilder\MyWorldApiResponseBuilder;
use Generated\Shared\DataBuilder\PaymentCodeGenerateRequestBuilder;
use Generated\Shared\DataBuilder\PaymentCodeValidateRequestBuilder;
use Generated\Shared\DataBuilder\PaymentCodeValidateResponseBuilder;
use Generated\Shared\DataBuilder\PaymentConfirmationRequestBuilder;
use Generated\Shared\DataBuilder\PaymentConfirmationResponseBuilder;
use Generated\Shared\DataBuilder\PaymentDataRequestBuilder;
use Generated\Shared\DataBuilder\PaymentDataResponseBuilder;
use Generated\Shared\DataBuilder\PaymentSessionResponseBuilder;
use Generated\Shared\DataBuilder\PaymentTransactionBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShoppingPointsDealBuilder;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Generated\Shared\Transfer\CustomerBalanceTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateResponseTransfer;
use Generated\Shared\Transfer\PaymentConfirmationResponseTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\PaymentSessionResponseTransfer;
use Generated\Shared\Transfer\PaymentTransactionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig;

class DataHelper
{
    public const CUSTOMER_SERVICE_CASHBACK_DEFAULT_BALANCE = 5000;
    public const CUSTOMER_SERVICE_SHOPPING_POINTS_DEFAULT_BALANCE = 49.55;
    public const CUSTOMER_SERVICE_E_VOUCHER_DEFAULT_BALANCE = 5000;
    public const CUSTOMER_SERVICE_MARKETER_DEFAULT_BALANCE = 5000;
    public const CUSTOMER_SERVICE_BENEFIT_EVOUCHER_DEFAULT_BALANCE = 5000;

    protected const API_FACADE_DEFAULT_TRANSACTION_ACCEPTED = [
        PaymentTransactionTransfer::PAYMENT_OPTION_ID => 12,
        PaymentTransactionTransfer::AMOUNT => 1000,
        PaymentTransactionTransfer::STATUS => MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_NAME_ACCEPTED,
        PaymentTransactionTransfer::BATCH_NUMBER => 123412341234,
        PaymentTransactionTransfer::DATE_TIME => '2020-06-14T15:28:17Z',
        PaymentTransactionTransfer::STATUS_CODE => MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_ACCEPTED,
        PaymentTransactionTransfer::UNIT => 'Currency',
        PaymentTransactionTransfer::UNIT_TYPE => 'USD',
    ];

    public const RESPONSE_DEFAULT_SEED = [
        MyWorldApiResponseTransfer::IS_SUCCESS => true,
    ];

    public const PAYMENT_CONFIRM_RESPONSE_SEED = [
        PaymentConfirmationResponseTransfer::PAYMENT_ID => 'payment_id_string',
    ];

    public const PAYMENT_SESSION_RESPONSE_SEED = [
        PaymentSessionResponseTransfer::SESSION_ID => 'session_id_string',
        PaymentSessionResponseTransfer::TWO_FACTOR_AUTH => ['SMS'],
    ];

    public const PAYMENT_CODE_VALIDATION_RESPONSE = [
        PaymentCodeValidateResponseTransfer::IS_VALID => true,
        PaymentCodeValidateResponseTransfer::DESCRIPTION => '',
    ];

    public const PAYMENT_DATA_DEFAULT_DATA = [
        PaymentDataResponseTransfer::PAYMENT_ID => 'payment_id',
        PaymentDataResponseTransfer::AMOUNT => 1230,
        PaymentDataResponseTransfer::REFERENCE => 'reference_string',
        PaymentDataResponseTransfer::CURRENCY_ID => 'USD',
        PaymentDataResponseTransfer::STATUS => MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_NAME_ACCEPTED,
    ];

    public const ITEM_DEFAULT_SEED = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::UNIT_GROSS_PRICE => 1212,
        ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1212,
        ItemTransfer::SUM_GROSS_PRICE => 1212,
    ];

    public const SHOPPING_POINTS_DEAL_DEFAULT_SEED = [
        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 2,
        ShoppingPointsDealTransfer::PRICE => 1000,
        ShoppingPointsDealTransfer::IS_ACTIVE => true,
    ];

    public const CURRENCY_DEFAULT_SEED = [
        CurrencyTransfer::CODE => 'USD',
        CurrencyTransfer::NAME => 'US Dollar',
        CurrencyTransfer::SYMBOL => '$',
    ];

    public const SSO_ACCESS_TOKEN_DEFAULT_SEED = [
        SsoAccessTokenTransfer::ACCESS_TOKEN => 'test_access_token',
        SsoAccessTokenTransfer::EXPIRES_IN => 1800,
        SsoAccessTokenTransfer::TOKEN_TYPE => 'Bearer',
        SsoAccessTokenTransfer::ID_TOKEN => 12,
        SsoAccessTokenTransfer::REFRESH_TOKEN => 'test_refresh_token',
    ];

    public const CUSTOMER_BALANCE_DEFAULT_SEED = [
        CustomerBalanceTransfer::AVAILABLE_BENEFIT_VOUCHER_AMOUNT => 5000,
        CustomerBalanceTransfer::AVAILABLE_BENEFIT_VOUCHER_CURRENCY => 'USD',
        CustomerBalanceTransfer::AVAILABLE_CASHBACK_AMOUNT => 5000,
        CustomerBalanceTransfer::AVAILABLE_CASHBACK_CURRENCY => 'USD',
        CustomerBalanceTransfer::AVAILABLE_SHOPPING_POINT_AMOUNT => 50.00,
    ];

    public const CUSTOMER_DEFAULT_SEED = [
        CustomerTransfer::SSO_ACCESS_TOKEN => self::SSO_ACCESS_TOKEN_DEFAULT_SEED,
        CustomerTransfer::MY_WORLD_CUSTOMER_ID => 'default_customer_id',
        CustomerTransfer::MY_WORLD_CUSTOMER_NUMBER => 'default_customer_number',
        CustomerTransfer::IS_ACTIVE => true,
        CustomerTransfer::CUSTOMER_TYPE => 'Customer',
    ];

    public const QUOTE_DEFAULT_SEED = [
        QuoteTransfer::CURRENCY => self::CURRENCY_DEFAULT_SEED,
    ];

    public const BENEFIT_VOUCHER_DEAL_DEFAULT_SEED = [
        BenefitVoucherDealDataTransfer::AMOUNT => 222,
        BenefitVoucherDealDataTransfer::IS_STORE => true,
        BenefitVoucherDealDataTransfer::SALES_PRICE => 1000,
    ];

    public const CALCULABLE_OBJECT_DEFAULT_SEED = [
        CalculableObjectTransfer::TOTALS => self::TOTAL_DEFAULT_SEED,
    ];

    public const TOTAL_DEFAULT_SEED = [
        TotalsTransfer::GRAND_TOTAL => 1212,
    ];

    public const CUSTOMER_BALANCE_BY_CURRENCY_DEFAULT_SEED = [
        CustomerBalanceByCurrencyTransfer::CURRENCY_ID => 'USD',
        CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 12.12,
        CustomerBalanceByCurrencyTransfer::TARGET_CURRENCY_ID => 'USD',
        CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 12.12,
        CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
    ];

    /**
     * @return \ArrayObject
     */
    protected function createTransactions(): ArrayObject
    {
        return new ArrayObject([
            (new PaymentTransactionBuilder(self::API_FACADE_DEFAULT_TRANSACTION_ACCEPTED))->build(),
        ]);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\MyWorldApiResponseBuilder
     */
    public function createResponseBuilder(bool $setDefaultSeed = true, array $seed = []): MyWorldApiResponseBuilder
    {
        $default = $setDefaultSeed ? self::RESPONSE_DEFAULT_SEED + $seed : $seed;

        if ($setDefaultSeed) {
            $default[MyWorldApiResponseTransfer::PAYMENT_SESSION_RESPONSE] = $this->createPaymentSessionResponseBuilder()->build();
            $default[MyWorldApiResponseTransfer::PAYMENT_CODE_VALIDATE_RESPONSE] = $this->createPaymentCodeValidationResponseBuilder()->build();
            $default[MyWorldApiResponseTransfer::PAYMENT_CONFIRMATION_RESPONSE_TRANSFER] = $this->createPaymentConfirmationResponseBuilder()->build();
            $default[MyWorldApiResponseTransfer::PAYMENT_DATA_RESPONSE] = $this->createPaymentDataResponseBuilder()->build();
        }

        return new MyWorldApiResponseBuilder($default);
    }

    /**
     * @param bool $setDefault
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\PaymentDataResponseBuilder
     */
    public function createPaymentDataResponseBuilder(bool $setDefault = true, array $seed = []): PaymentDataResponseBuilder
    {
        $default = $setDefault ? self::PAYMENT_DATA_DEFAULT_DATA + $seed : $seed;

        if ($setDefault) {
            $default[PaymentDataResponseTransfer::TRANSACTIONS] = $this->createTransactions();
        }

        return new PaymentDataResponseBuilder($default);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\PaymentConfirmationResponseBuilder
     */
    public function createPaymentConfirmationResponseBuilder(bool $setDefaultSeed = true, array $seed = []): PaymentConfirmationResponseBuilder
    {
        $default = $setDefaultSeed ? self::PAYMENT_CONFIRM_RESPONSE_SEED + $seed : $seed;

        return new PaymentConfirmationResponseBuilder($default);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\PaymentSessionResponseBuilder
     */
    public function createPaymentSessionResponseBuilder(bool $setDefaultSeed = true, array $seed = []): PaymentSessionResponseBuilder
    {
        $default = $setDefaultSeed ? self::PAYMENT_SESSION_RESPONSE_SEED + $seed : $seed;

        return new PaymentSessionResponseBuilder($default);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\PaymentCodeValidateResponseBuilder
     */
    public function createPaymentCodeValidationResponseBuilder(bool $setDefaultSeed = true, array $seed = []): PaymentCodeValidateResponseBuilder
    {
        $default = $setDefaultSeed ? self::PAYMENT_CODE_VALIDATION_RESPONSE + $seed : $seed;

        return new PaymentCodeValidateResponseBuilder($default);
    }

    /**
     * @return \ArrayObject
     */
    public function getDefaultListCustomerBalanceByCurrencyTransfers(): ArrayObject
    {
        $eVoucherBalance = $this->createCustomerBalanceByCurrencyBuilder(true, [
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_NAME => 'EVoucher',
        ])->build();
        $cashbackBalance = $this->createCustomerBalanceByCurrencyBuilder(true, [
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_NAME => 'Cashback',
        ])->build();
        $eVoucherOnBehalfOfMarket = $this->createCustomerBalanceByCurrencyBuilder(true, [
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_NAME => 'EVoucherMarketer',
        ])->build();

        return new ArrayObject([$eVoucherBalance, $cashbackBalance, $eVoucherOnBehalfOfMarket]);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\CustomerBalanceByCurrencyBuilder
     */
    public function createCustomerBalanceByCurrencyBuilder(bool $setDefaultSeed = true, array $seed = []): CustomerBalanceByCurrencyBuilder
    {
        return new CustomerBalanceByCurrencyBuilder($setDefaultSeed ? self::CUSTOMER_BALANCE_BY_CURRENCY_DEFAULT_SEED + $seed : $seed);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\CalculableObjectBuilder
     */
    public function createCalculableObjectBuilder(bool $setDefaultSeed = true, array $seed = []): CalculableObjectBuilder
    {
        $defaultSeed = self::CALCULABLE_OBJECT_DEFAULT_SEED;

        if ($setDefaultSeed) {
            $defaultSeed[CalculableObjectTransfer::ORIGINAL_QUOTE] = $this->createQuoteBuilder()->build();
        }

        return new CalculableObjectBuilder($setDefaultSeed ? $defaultSeed + $seed : $seed);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\CustomerBuilder
     */
    public function createCustomerBuilder(bool $setDefaultSeed = true, array $seed = []): CustomerBuilder
    {
        $defaultSeed = self::CUSTOMER_DEFAULT_SEED + [
            CustomerTransfer::BALANCES => $this->getDefaultListCustomerBalanceByCurrencyTransfers(),
        ];

        return new CustomerBuilder($setDefaultSeed ? $defaultSeed + $seed : $seed);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\QuoteBuilder
     */
    public function createQuoteBuilder(bool $setDefaultSeed = true, array $seed = []): QuoteBuilder
    {
        $defaultSeed = self::QUOTE_DEFAULT_SEED + [
            QuoteTransfer::CUSTOMER => $this->createCustomerBuilder()->build(),
        ];

        return new QuoteBuilder($setDefaultSeed ? $defaultSeed + $seed : $seed);
    }

    /**
     * @param bool $setDefaultSeed
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    public function createItemBuilderWithShoppingPointsDeal(bool $setDefaultSeed = true): ItemBuilder
    {
        $shoppingPointsDealSeedData = $this->createShoppingPointsDealBuilder($setDefaultSeed)->getSeedData();

        return $this->createItemBuilder([
                ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => $shoppingPointsDealSeedData[ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY],
                ItemTransfer::USE_SHOPPING_POINTS => $shoppingPointsDealSeedData[ShoppingPointsDealTransfer::IS_ACTIVE],
                ItemTransfer::SHOPPING_POINTS_DEAL => $shoppingPointsDealSeedData,
            ]);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    public function createItemBuilderWithBenefitVoucherDeal(array $overrideData = []): ItemBuilder
    {
        $seedData = array_merge(
            self::ITEM_DEFAULT_SEED,
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => self::BENEFIT_VOUCHER_DEAL_DEFAULT_SEED,
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => self::BENEFIT_VOUCHER_DEAL_DEFAULT_SEED[BenefitVoucherDealDataTransfer::AMOUNT],
            ],
            $overrideData
        );

        return $this->createItemBuilder($seedData);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    public function createItemBuilder(array $overrideData = []): ItemBuilder
    {
        return new ItemBuilder(array_merge(self::ITEM_DEFAULT_SEED, $overrideData));
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $override
     *
     * @return \Generated\Shared\DataBuilder\ShoppingPointsDealBuilder
     */
    public function createShoppingPointsDealBuilder(bool $setDefaultSeed = true, array $override = []): ShoppingPointsDealBuilder
    {
        return new ShoppingPointsDealBuilder(
            $setDefaultSeed ? self::SHOPPING_POINTS_DEAL_DEFAULT_SEED + $override : $override
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setDefaultCustomerTransferData(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $customerTransfer->fromArray($this->createCustomerBuilder()->build()->toArray(), true);
    }

    /**
     * @param bool $setDefaultSeed
     * @param array $seed
     *
     * @return \Generated\Shared\DataBuilder\CustomerBalanceBuilder
     */
    public function createCustomerBalanceBuilder(bool $setDefaultSeed = true, array $seed = []): CustomerBalanceBuilder
    {
        return new CustomerBalanceBuilder($setDefaultSeed ? self::CUSTOMER_BALANCE_DEFAULT_SEED + $seed : $seed);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\MyWorldApiRequestBuilder
     */
    public function createApiRequestBuilder(array $overrideData = []): MyWorldApiRequestBuilder
    {
        return new MyWorldApiRequestBuilder($overrideData);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\PaymentCodeGenerateRequestBuilder
     */
    public function createSmsCodeGenerationRequestBuilder(array $overrideData = []): PaymentCodeGenerateRequestBuilder
    {
        return new PaymentCodeGenerateRequestBuilder($overrideData);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\PaymentCodeValidateRequestBuilder
     */
    public function createPaymentCodeValidationRequestBuilder(array $overrideData = []): PaymentCodeValidateRequestBuilder
    {
        return new PaymentCodeValidateRequestBuilder($overrideData);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\PaymentConfirmationRequestBuilder
     */
    public function createConfirmPaymentRequestBuilder(array $overrideData = []): PaymentConfirmationRequestBuilder
    {
        return new PaymentConfirmationRequestBuilder($overrideData);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\DataBuilder\PaymentDataRequestBuilder
     */
    public function createGetPaymentRequestBuilder(array $overrideData = []): PaymentDataRequestBuilder
    {
        return new PaymentDataRequestBuilder($overrideData);
    }
}
