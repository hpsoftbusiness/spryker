<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Shared\Catalog\CatalogConfig;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractCustomerGroupFacetPageMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractCustomerGroupFacetPageMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractCustomerGroupFacetPageMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    protected const KEY_PRODUCT_LIST = 'product_list_map';
    protected const KEY_WHITE_LIST = 'whitelists';
    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractCustomerGroupFacetPageMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractCustomerGroupFacetPageMapExpanderPlugin();
    }

    /**
     * @dataProvider productAttributeDataProvider
     *
     * @param array $productTestedData
     * @param string[] $expectedFacetValue
     *
     * @return void
     */
    public function testSellableCountriesCollectedForFacet(array $productTestedData, array $expectedFacetValue): void
    {
        $productData = $this->getProductData($productTestedData);
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        $facet = $pageMapTransfer->getStringFacet()[0];
        self::assertEquals(CatalogConfig::PRODUCT_ABSTRACT_CUSTOMER_GROUP_FACET_NAME, $facet->getName());
        self::assertEquals($expectedFacetValue, $facet->getValue());
    }

    /**
     * @return array[][]
     */
    public function productAttributeDataProvider(): array
    {
        return [
            'customerGroupCollect' => [
                [
                    self::KEY_PRODUCT_LIST => [self::KEY_WHITE_LIST => [1, 2, 3, 4, 5]],
                ],
                [
                    1,
                    2,
                    3,
                    4,
                    5,
                ],
            ],
            'oneCustomerGroupCollect' => [
                [
                    self::KEY_PRODUCT_LIST => [self::KEY_WHITE_LIST => [1]],
                ],
                [1],
            ],
            'noneCustomerGroupCollect' => [
                [],
                [],
            ],

        ];
    }
}
