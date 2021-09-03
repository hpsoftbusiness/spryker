<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Generated\Shared\Transfer\SpDealTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractShoppingPointDealsExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractShoppingPointDealsExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractShoppingPointDealsExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_SHOPPING_POINT_DEALS = 'shopping-point-deals';
    private const ATTRIBUTE_SP_DEAL = 'sp-deal';
    private const ATTRIBUTE_SP_DEAL_SP_AMOUNT = 'spAmount';
    private const ATTRIBUTE_SP_DEAL_SP_ITEM_PRICE = 'spItemPrice';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractShoppingPointDealsExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractShoppingPointDealsExpanderPlugin();
    }

    /**
     * @dataProvider productAttributesProvider
     *
     * @param array $attributeData
     * @param bool $expectedShoppingDeals
     * @param array|null $expectedSpDeal
     *
     * @return void
     */
    public function testProductAttributesMappedWithCorrectTypes(
        array $attributeData,
        bool $expectedShoppingDeals,
        ?array $expectedSpDeal
    ): void {
        $restCatalogSearchAbstractProductsTransfer = new RestCatalogSearchAbstractProductsTransfer();
        $this->sut->expand($attributeData, $restCatalogSearchAbstractProductsTransfer);

        $shoppingPointDeals = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::SHOPPING_POINT_DEALS] ?? null;

        $spDeal = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::SP_DEAL] ?? null;

        self::assertEquals($expectedShoppingDeals, $shoppingPointDeals);
        self::assertEquals($expectedSpDeal, $spDeal);
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'existAllCorrectData' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEALS => true,
                    self::ATTRIBUTE_SP_DEAL => [
                        self::ATTRIBUTE_SP_DEAL_SP_ITEM_PRICE => 5,
                        self::ATTRIBUTE_SP_DEAL_SP_AMOUNT => 3,
                    ],
                ],
                true,
                [
                    SpDealTransfer::SP_AMOUNT => 3,
                    SpDealTransfer::SP_ITEM_PRICE => 5,
                ],
            ],
            'existIncorrectSpDealData' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEALS => false,
                    self::ATTRIBUTE_SP_DEAL => [
                        self::ATTRIBUTE_SP_DEAL_SP_ITEM_PRICE => 5,
                        self::ATTRIBUTE_SP_DEAL_SP_AMOUNT => 3,
                    ],
                ],
                false,
                null,
            ],
            'withoutSpDealData' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_DEALS => true,
                ],
                true,
                null,
            ],
            'withoutData' => [
                [],
                false,
                null,
            ],
        ];
    }
}
