<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractShoppingPointsCashbackAmountExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractShoppingPointsCashbackAmountExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractShoppingPointsCashbackAmountExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';
    private const ATTRIBUTE_SHOPPING_POINT_DEAL = 'shopping-point-deals';
    private const ATTRIBUTE_BENEFIT_DEAL = 'benefit-deals';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractShoppingPointsCashbackAmountExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractShoppingPointsCashbackAmountExpanderPlugin();
    }

    /**
     * @dataProvider productAttributesProvider
     *
     * @param array $attributeData
     * @param int|null $expectedCashBackAmount
     * @param int|null $expectedShoppingPoints
     *
     * @return void
     */
    public function testProductAttributesMappedWithCorrectTypes(
        array $attributeData,
        ?int $expectedCashBackAmount,
        ?int $expectedShoppingPoints
    ): void {
        $restCatalogSearchAbstractProductsTransfer = new RestCatalogSearchAbstractProductsTransfer();
        $this->sut->expand($attributeData, $restCatalogSearchAbstractProductsTransfer);

        $cashbackAmount = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::CASHBACK_AMOUNT] ?? [];
        $shoppingPoints = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::SHOPPING_POINTS] ?? [];

        self::assertEquals($expectedCashBackAmount, $cashbackAmount);
        self::assertEquals($expectedShoppingPoints, $shoppingPoints);
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'withDealsAreFalse' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => false,
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 3,
                    self::ATTRIBUTE_SHOPPING_POINTS => 5,

                ],
                3,
                5,
            ],
            'withDealsAreFalseWithoutShoppingPoints' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => false,
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 3,
                ],
                3,
                0,
            ],
            'withDealsAreFalseWithoutCashback' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => false,
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_SHOPPING_POINTS => 5,
                ],
                0,
                5,
            ],
            'withShoppingPointDealsAreTrue' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => true,
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 3,
                    self::ATTRIBUTE_SHOPPING_POINTS => 5,
                ],
                0,
                0,
            ],
            'withBenefitDealsAreTrue' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => false,
                    self::ATTRIBUTE_BENEFIT_DEAL => true,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 3,
                    self::ATTRIBUTE_SHOPPING_POINTS => 5,
                ],
                0,
                0,
            ],
            'withIntValueAsString' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => false,
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => '3',
                    self::ATTRIBUTE_SHOPPING_POINTS => '5',

                ],
                3,
                5,
            ],
            'withIncorrectData' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEAL => false,
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 'string',
                    self::ATTRIBUTE_SHOPPING_POINTS => '5',

                ],
                0,
                5,
            ],
        ];
    }
}
