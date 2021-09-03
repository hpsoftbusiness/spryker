<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BvDealTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractBenefitDealsExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractBenefitDealsExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractBenefitDealsExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_BENEFIT_DEAL = 'benefit-deals';
    private const ATTRIBUTE_BV_DEAL = 'bv-deal';
    private const ATTRIBUTE_BV_DEAL_BV_AMOUNT = 'bvAmount';
    private const ATTRIBUTE_BV_DEAL_BV_ITEM_PRICE = 'bvItemPrice';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractBenefitDealsExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractBenefitDealsExpanderPlugin();
    }

    /**
     * @dataProvider productAttributesProvider
     *
     * @param array $attributeData
     * @param bool $expectedBenefitDeals
     * @param array|null $expectedBvDeal
     *
     * @return void
     */
    public function testProductAttributesMappedWithCorrectTypes(
        array $attributeData,
        bool $expectedBenefitDeals,
        ?array $expectedBvDeal
    ): void {
        $restCatalogSearchAbstractProductsTransfer = new RestCatalogSearchAbstractProductsTransfer();
        $this->sut->expand($attributeData, $restCatalogSearchAbstractProductsTransfer);

        $benefitDeals = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::BENEFIT_DEALS] ?? null;

        $bvDeal = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::BV_DEAL] ?? null;

        self::assertEquals($expectedBenefitDeals, $benefitDeals);
        self::assertEquals($expectedBvDeal, $bvDeal);
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'existAllCorrectData' => [
                [
                    self::ATTRIBUTE_BENEFIT_DEAL => true,
                    self::ATTRIBUTE_BV_DEAL => [
                        self::ATTRIBUTE_BV_DEAL_BV_ITEM_PRICE => 5,
                        self::ATTRIBUTE_BV_DEAL_BV_AMOUNT => 3,
                    ],
                ],
                true,
                [
                    BvDealTransfer::BV_AMOUNT => 3,
                    BvDealTransfer::BV_ITEM_PRICE => 5,
                ],
            ],
            'existIncorrectBvDealData' => [
                [
                    self::ATTRIBUTE_BENEFIT_DEAL => false,
                    self::ATTRIBUTE_BV_DEAL => [
                        self::ATTRIBUTE_BV_DEAL_BV_ITEM_PRICE => 5,
                        self::ATTRIBUTE_BV_DEAL_BV_AMOUNT => 3,
                    ],
                ],
                false,
                null,
            ],
            'withoutBvDealData' => [
                [
                    self::ATTRIBUTE_BENEFIT_DEAL => true,
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
