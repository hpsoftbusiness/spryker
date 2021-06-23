<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Discount;

use Codeception\Module;
use Generated\Shared\DataBuilder\DiscountableItemBuilder;
use Generated\Shared\DataBuilder\DiscountBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Pyz\Shared\Discount\DiscountConstants;
use Pyz\Shared\PriceProduct\PriceProductConfig;

class DataHelper extends Module
{
    public const ITEM_DATA_SP = [
        ItemTransfer::QUANTITY => 2,
        ItemTransfer::USE_SHOPPING_POINTS => true,
        ItemTransfer::UNIT_PRICE => 1500,
        ItemTransfer::UNIT_GROSS_PRICE => 1500,
        ItemTransfer::SHOPPING_POINTS_DEAL => [
            ShoppingPointsDealTransfer::PRICE => 1200,
            ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
            ShoppingPointsDealTransfer::IS_ACTIVE => true,
        ],
    ];

    public const ITEM_DATA_SP_2 = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::USE_SHOPPING_POINTS => true,
        ItemTransfer::UNIT_PRICE => 800,
        ItemTransfer::UNIT_GROSS_PRICE => 800,
        ItemTransfer::SHOPPING_POINTS_DEAL => [
            ShoppingPointsDealTransfer::PRICE => 600,
            ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
            ShoppingPointsDealTransfer::IS_ACTIVE => true,
        ],
    ];

    public const ITEM_DATA_WITH_MISSING_SP_DEAL = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::USE_SHOPPING_POINTS => true,
        ItemTransfer::UNIT_PRICE => 1000,
        ItemTransfer::UNIT_GROSS_PRICE => 1000,
    ];

    public const ITEM_DATA_DEFAULT = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::UNIT_PRICE => 1000,
        ItemTransfer::UNIT_GROSS_PRICE => 1000,
    ];

    public const QUOTE_METADATA = [
        QuoteTransfer::CURRENCY => [
            CurrencyTransfer::CODE => 'EUR',
        ],
        QuoteTransfer::PRICE_MODE => PriceProductConfig::PRICE_GROSS_MODE,
        QuoteTransfer::STORE => [
            StoreTransfer::ID_STORE => 1,
        ],
    ];

    public const INTERNAL_DISCOUNT_COLLECTOR_PLUGIN_NAME = 'collector';

    public const DISCOUNT_DATA = [
        DiscountTransfer::DISPLAY_NAME => 'Internal discount',
        DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_INTERNAL_DISCOUNT,
        DiscountTransfer::COLLECTOR_PLUGIN => self::INTERNAL_DISCOUNT_COLLECTOR_PLUGIN_NAME,
    ];

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuoteTransfer(array $overrideData = []): QuoteTransfer
    {
        return (new QuoteBuilder(array_merge(self::QUOTE_METADATA, $overrideData)))->build();
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function buildDiscountTransfer(array $overrideData = []): DiscountTransfer
    {
        return (new DiscountBuilder($overrideData))->build();
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    public function buildDiscountableItemTransfer(array $overrideData = []): DiscountableItemTransfer
    {
        return (new DiscountableItemBuilder($overrideData))->build();
    }
}
