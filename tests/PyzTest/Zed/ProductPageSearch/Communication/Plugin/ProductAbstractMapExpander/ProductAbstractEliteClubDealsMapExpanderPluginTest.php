<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractEliteClubDealsMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractEliteClubDealsMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractEliteClubDealsMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    private const KEY_PRODUCT_LIST = 'product_list_map';
    private const KEY_WHITE_LIST = 'whitelists';

    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractEliteClubDealsMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractEliteClubDealsMapExpanderPlugin();
    }

    /**
     * @dataProvider productDataProvider
     *
     * @param array $productAttributes
     * @param bool $ecDeals
     * @param bool $onlyEcDeal
     *
     * @return void
     */
    public function testBenefitStoresFlagMappedCorrectly(
        array $productAttributes,
        bool $ecDeals,
        bool $onlyEcDeal
    ): void {
        $productData = $this->getProductDataFromAttributes($productAttributes);
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        self::assertEquals($ecDeals, $pageMapTransfer->getEcDeals());
        self::assertEquals($onlyEcDeal, $pageMapTransfer->getOnlyEcDeals());
    }

    /**
     * @return array
     */
    public function productDataProvider(): array
    {
        return [
            'customerGroupCollect' => [
                [
                    self::KEY_PRODUCT_LIST => [self::KEY_WHITE_LIST => [1, 2, 3, 4, 5]],
                ],
                false,
                false,
            ],
            'oneCustomerGroupCollect' => [
                [
                    self::KEY_PRODUCT_LIST => [self::KEY_WHITE_LIST => [1]],
                ],
                false,
                false,
            ],
            'noneCustomerGroupCollect' => [
                [],
                false,
                false,
            ],
//            'OneCustomerGroup_6Collect' => [
//                [6],
//                true,
//                true,
//            ],
//            'CustomerGroup_6ExistCollect' => [
//                [1, 2, 3, 6],
//                true,
//                false,
//            ],
        ];
    }
}
