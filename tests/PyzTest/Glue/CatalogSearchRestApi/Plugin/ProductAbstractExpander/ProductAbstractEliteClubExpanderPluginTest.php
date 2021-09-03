<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractEliteClubExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractEliteClubExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractEliteClubExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_ELITE_CLUB_DEAL = 'ec_deals';
    private const ATTRIBUTE_ONLY_ELITE_CLUB_DEAL = 'only_ec_deals';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractEliteClubExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractEliteClubExpanderPlugin();
    }

    /**
     * @dataProvider productAttributesProvider
     *
     * @param array $attributeData
     * @param bool|null $expectedEliteClubDeal
     * @param bool|null $expectedOnlyEliteClubDeal
     *
     * @return void
     */
    public function testProductAttributesMappedWithCorrectTypes(
        array $attributeData,
        ?bool $expectedEliteClubDeal,
        ?bool $expectedOnlyEliteClubDeal
    ): void {
        $restCatalogSearchAbstractProductsTransfer = new RestCatalogSearchAbstractProductsTransfer();
        $this->sut->expand($attributeData, $restCatalogSearchAbstractProductsTransfer);

        $eliteClubDeal = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::ELITE_CLUB] ?? null;

        $onlyEliteClubDeal = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::ELITE_CLUB_ONLY] ?? null;

        self::assertEquals($expectedEliteClubDeal, $eliteClubDeal);
        self::assertEquals($expectedOnlyEliteClubDeal, $onlyEliteClubDeal);
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'allTrueData' => [
                [
                    self::ATTRIBUTE_ELITE_CLUB_DEAL => true,
                    self::ATTRIBUTE_ONLY_ELITE_CLUB_DEAL => true,
                ],
                true,
                true,
            ],
            'allFalseData' => [
                [
                    self::ATTRIBUTE_ELITE_CLUB_DEAL => false,
                    self::ATTRIBUTE_ONLY_ELITE_CLUB_DEAL => false,
                ],
                false,
                false,
            ],
            'oneTrueData' => [
                [
                    self::ATTRIBUTE_ELITE_CLUB_DEAL => true,
                    self::ATTRIBUTE_ONLY_ELITE_CLUB_DEAL => false,
                ],
                true,
                false,
            ],
            'withoutData' => [
                [],
                null,
                null,
            ],

        ];
    }
}
