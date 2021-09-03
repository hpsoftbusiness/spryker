<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractSellableExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractSellableExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractSellableExpanderPluginTest extends Unit
{
    private const ATTRIBUTES_KEY = 'attributes';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractSellableExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractSellableExpanderPlugin();
    }

    /**
     * @dataProvider productAttributesProvider
     *
     * @param array $attributeData
     * @param array $expectedData
     *
     * @return void
     */
    public function testProductAttributesMappedWithCorrectTypes(array $attributeData, array $expectedData): void
    {
        $restCatalogSearchAbstractProductsTransfer = new RestCatalogSearchAbstractProductsTransfer();
        $this->sut->expand($attributeData, $restCatalogSearchAbstractProductsTransfer);

        $sellableAttributes = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::SELLABLE] ?? [];

        self::assertEquals($expectedData, $sellableAttributes);
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'allTrueData' => [
                [
                    self::ATTRIBUTES_KEY => [
                        "sellable_at" => true,
                        "sellable_cz" => true,
                        "sellable_fi" => true,
                        "sellable_de" => true,
                        "sellable_hu" => true,
                        "sellable_it" => true,
                        "sellable_no" => true,
                        "sellable_pl" => true,
                        "sellable_pt" => true,
                        "sellable_ch" => true,

                    ],
                ],
                [
                    "at" => true,
                    "cz" => true,
                    "fi" => true,
                    "de" => true,
                    "hu" => true,
                    "it" => true,
                    "no" => true,
                    "pl" => true,
                    "pt" => true,
                    "ch" => true,
                ],
            ],
            'oneTrueData' => [
                [
                    self::ATTRIBUTES_KEY => [
                        "sellable_at" => true,
                        "sellable_cz" => false,
                        "sellable_fi" => false,
                        "sellable_de" => false,
                        "sellable_hu" => false,
                        "sellable_it" => false,
                        "sellable_no" => false,
                        "sellable_pl" => false,
                        "sellable_pt" => false,
                        "sellable_ch" => false,

                    ],
                ],
                [
                    "at" => true,
                    "cz" => false,
                    "fi" => false,
                    "de" => false,
                    "hu" => false,
                    "it" => false,
                    "no" => false,
                    "pl" => false,
                    "pt" => false,
                    "ch" => false,
                ],
            ],
            'allFalseData' => [
                [
                    self::ATTRIBUTES_KEY => [
                        "sellable_at" => false,
                        "sellable_cz" => false,
                        "sellable_fi" => false,
                        "sellable_de" => false,
                        "sellable_hu" => false,
                        "sellable_it" => false,
                        "sellable_no" => false,
                        "sellable_pl" => false,
                        "sellable_pt" => false,
                        "sellable_ch" => false,

                    ],
                ],
                [
                    "at" => false,
                    "cz" => false,
                    "fi" => false,
                    "de" => false,
                    "hu" => false,
                    "it" => false,
                    "no" => false,
                    "pl" => false,
                    "pt" => false,
                    "ch" => false,
                ],
            ],
        ];
    }
}
