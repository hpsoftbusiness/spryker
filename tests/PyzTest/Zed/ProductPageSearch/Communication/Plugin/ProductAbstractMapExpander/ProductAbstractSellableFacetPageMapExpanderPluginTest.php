<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Shared\Catalog\CatalogConfig;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractSellableFacetPageMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractSellableFacetPageMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractSellableFacetPageMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractSellableFacetPageMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractSellableFacetPageMapExpanderPlugin();
    }

    /**
     * @dataProvider productAttributeDataProvider
     *
     * @param array $productAttributes
     * @param string[] $expectedFacetValue
     *
     * @return void
     */
    public function testSellableCountriesCollectedForFacet(array $productAttributes, array $expectedFacetValue): void
    {
        $productData = $this->getProductDataFromAttributes($productAttributes);
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        $sellableFacet = $pageMapTransfer->getStringFacet()[0];
        self::assertEquals(CatalogConfig::PRODUCT_ABSTRACT_SELLABLE_FACET_NAME, $sellableFacet->getName());
        self::assertEquals($expectedFacetValue, $sellableFacet->getValue());
    }

    /**
     * @return array[][]
     */
    public function productAttributeDataProvider(): array
    {
        return [
            'countriesCollected' => [
                [
                    'brand' => 'Samsung',
                    'color' => 'Black',
                    'sellable_de' => true,
                    'sellable_pl' => true,
                    'sellable_ru' => true,
                    'sellable_lt' => false,
                ],
                [
                    'de', 'pl', 'ru',
                ],
            ],
            'emptyArrayAssignedIfNotSellableForAnyCountry' => [
                [
                    'brand' => 'Samsung',
                    'sellable_de' => false,
                    'sellable_pl' => false,
                ],
                [],
            ],
            'emptyArrayAssignedIfSellableAttributesMissing' => [
                [
                    'brand' => 'Samsung',
                ],
                [],
            ],
        ];
    }
}
