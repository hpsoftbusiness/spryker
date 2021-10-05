<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Shared\Catalog\CatalogConfig;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractNotSellableFacetPageMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractNotSellableFacetPageMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractNotSellableFacetPageMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractNotSellableFacetPageMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractNotSellableFacetPageMapExpanderPlugin();
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
        self::assertEquals(CatalogConfig::PRODUCT_ABSTRACT_NOT_SELLABLE_FACET_NAME, $sellableFacet->getName());
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
                    'sellable_de' => true,
                    'sellable_pl' => true,
                    'sellable_ru' => true,
                    'sellable_lt' => false,
                ],
                [
                    'lt',
                ],
            ],
            'emptyArrayAssignedIfNotSellableForAnyCountry' => [
                [
                    'sellable_de' => false,
                    'sellable_pl' => false,
                ],
                ['de', 'pl'],
            ],
            'emptyArrayAssignedIfSellableAttributesMissing' => [
                [],
                [],
            ],
        ];
    }
}
