<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractBenefitStoreDealsVoucherMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractBenefitStoreDealsVoucherMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractBenefitStoreDealsVoucherMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    private const KEY_ATTRIBUTES = 'attributes';
    private const KEY_ATTRIBUTE_BENEFIT_AMOUNT = 'benefit_amount';
    private const KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT = 'product_sp_amount';
    private const KEY_ATTRIBUTE_BENEFIT_STORE = 'benefit_store';
    private const KEY_ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const KEY_ATTRIBUTE_SHOPPING_POINT_STORE = 'shopping_point_store';
    private const KEY_PRICES = 'prices';
    private const KEY_PRICES_GROSS_MODE = 'GROSS_MODE';
    private const KEY_PRICES_DEFAULT = 'DEFAULT';
    private const KEY_PRICES_BENEFIT = 'BENEFIT';
    private const KEY_SP_ITEM_PRICE = 'sp_item_price';
    private const KEY_SP_AMOUNT = 'sp_amount';
    private const KEY_BV_ITEM_PRICE = 'bv_item_price';
    private const KEY_BV_AMOUNT = 'bv_amount';

    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractBenefitStoreDealsVoucherMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractBenefitStoreDealsVoucherMapExpanderPlugin();
    }

    /**
     * @dataProvider productDataProvider
     *
     * @param array $productTestedData
     * @param bool $shoppingPointDeals
     * @param bool $benefitDeals
     * @param array|null $spDeal
     * @param array|null $bvDeal
     *
     * @return void
     */
    public function testBenefitStoresFlagMappedCorrectly(
        array $productTestedData,
        bool $shoppingPointDeals,
        bool $benefitDeals,
        ?array $spDeal,
        ?array $bvDeal
    ): void {
        $productData = $this->getProductData($productTestedData);
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        self::assertEquals($benefitDeals, $pageMapTransfer->getBenefitDeals());
        self::assertEquals($shoppingPointDeals, $pageMapTransfer->getShoppingPointDeals());

        if ($pageMapTransfer->getSpDeal() !== null) {
            self::assertEquals($spDeal, $pageMapTransfer->getSpDeal()->toArray());
        } else {
            self::assertEquals($spDeal, $pageMapTransfer->getSpDeal());
        }

        if ($pageMapTransfer->getBvDeal() !== null) {
            self::assertEquals($bvDeal, $pageMapTransfer->getBvDeal()->toArray());
        } else {
            self::assertEquals($bvDeal, $pageMapTransfer->getBvDeal());
        }
    }

    /**
     * @return array
     */
    public function productDataProvider(): array
    {
        return [
            'flagsSetTrueIfCorrectDataPresent' => [
                [
                    self::KEY_PRICES => [
                        'EUR' =>
                            [
                                self::KEY_PRICES_GROSS_MODE => [
                                    self::KEY_PRICES_DEFAULT => 126,
                                    self::KEY_PRICES_BENEFIT => 123,
                                ],
                            ],
                    ],
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_SHOPPING_POINTS => 1,
                        self::KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT => 1,
                        self::KEY_ATTRIBUTE_BENEFIT_STORE => 1,
                        self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE => 1,
                    ],
                ],
                true,
                true,
                [self::KEY_SP_ITEM_PRICE => 123, self::KEY_SP_AMOUNT => 1],
                [self::KEY_BV_ITEM_PRICE => 123, self::KEY_BV_AMOUNT => 3],
            ],
            'flagsSetFalseIfBenefitPriceIsZero' => [
                [
                    self::KEY_PRICES => [
                        'EUR' =>
                            [
                                self::KEY_PRICES_GROSS_MODE => [
                                    self::KEY_PRICES_BENEFIT => 0,
                                ],
                            ],
                    ],
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_SHOPPING_POINTS => 1,
                        self::KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT => 1,
                        self::KEY_ATTRIBUTE_BENEFIT_STORE => 0,
                        self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE => 0,
                    ],
                ],
                false,
                false,
                null,
                null,
            ],
            'flagsSetFalseIfBenefitPriceNotExist' => [
                [
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_SHOPPING_POINTS => 1,
                        self::KEY_ATTRIBUTE_BENEFIT_AMOUNT => 3,
                    ],
                ],
                false,
                false,
                null,
                null,
            ],
            'flagsSetFalseIfShoppingPointIsZero' => [
                [
                    self::KEY_PRICES => [
                        'EUR' =>
                            [
                                self::KEY_PRICES_GROSS_MODE => [
                                    self::KEY_PRICES_DEFAULT => 126,
                                    self::KEY_PRICES_BENEFIT => 123,
                                ],
                            ],
                    ],
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_SHOPPING_POINTS => 0,
                        self::KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT => 1,
                        self::KEY_ATTRIBUTE_BENEFIT_STORE => 1,
                        self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE => 0,
                    ],
                ],
                false,
                true,
                null,
                [self::KEY_BV_ITEM_PRICE => 123, self::KEY_BV_AMOUNT => 3],
            ],
            'flagsSetFalseIfBenefitAmountIsZero' => [
                [
                    self::KEY_PRICES => [
                        'EUR' =>
                            [
                                self::KEY_PRICES_GROSS_MODE => [
                                    self::KEY_PRICES_DEFAULT => 123,
                                    self::KEY_PRICES_BENEFIT => 123,
                                ],
                            ],
                    ],
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_SHOPPING_POINTS => 3,
                        self::KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT => 1,
                        self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE => 1,
                    ],
                ],
                true,
                false,
                [self::KEY_SP_ITEM_PRICE => 123, self::KEY_SP_AMOUNT => 1],
                null,
            ],
            'flagsSetFalseIfBenefitAmountNotExist' => [
                [
                    self::KEY_PRICES => [
                        'EUR' =>
                            [
                                self::KEY_PRICES_GROSS_MODE => [
                                    self::KEY_PRICES_DEFAULT => 123,
                                    self::KEY_PRICES_BENEFIT => 123,
                                ],
                            ],
                    ],
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_SHOPPING_POINTS => 3,
                        self::KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT => 1,
                        self::KEY_ATTRIBUTE_BENEFIT_STORE => 1,
                        self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE => 1,
                    ],
                ],
                true,
                false,
                [self::KEY_SP_ITEM_PRICE => 123, self::KEY_SP_AMOUNT => 1],
                null,
            ],
            'flagsSetFalseIfShoppingPointNotExist' => [
                [
                    self::KEY_PRICES => [
                        'EUR' =>
                            [
                                self::KEY_PRICES_GROSS_MODE => [
                                    self::KEY_PRICES_DEFAULT => 126,
                                    self::KEY_PRICES_BENEFIT => 123,
                                ],
                            ],
                    ],
                    self::KEY_ATTRIBUTES => [
                        self::KEY_ATTRIBUTE_BENEFIT_STORE => 1,
                        self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE => 1,
                    ],
                ],
                false,
                true,
                null,
                [self::KEY_BV_ITEM_PRICE => 123, self::KEY_BV_AMOUNT => 3],
            ],
        ];
    }
}
