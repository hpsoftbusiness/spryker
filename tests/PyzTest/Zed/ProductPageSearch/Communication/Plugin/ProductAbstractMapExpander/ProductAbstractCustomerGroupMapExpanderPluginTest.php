<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractCustomerGroupMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractCustomerGroupMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractCustomerGroupMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    protected const KEY_PRODUCT_LIST = 'product_list_map';
    protected const KEY_WHITE_LIST = 'whitelists';

    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractCustomerGroupMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractCustomerGroupMapExpanderPlugin();
    }

    /**
     * @dataProvider productDataProvider
     *
     * @param array $productTestedData
     * @param array $customerGroup
     *
     * @return void
     */
    public function testBenefitStoresFlagMappedCorrectly(
        array $productTestedData,
        array $customerGroup
    ): void {
        $productData = $this->getProductData($productTestedData);
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        self::assertEquals($customerGroup, $pageMapTransfer->getCustomerGroup());
    }

    /**
     * @return array[][]
     */
    public function productDataProvider(): array
    {
        return [
            'customerGroupCollect' => [
                [
                    self::KEY_PRODUCT_LIST => [self::KEY_WHITE_LIST => [1, 2, 3, 4, 5, 6]],
                ],
                [
                    'customer_group_1' => true,
                    'customer_group_2' => true,
                    'customer_group_3' => true,
                    'customer_group_4' => true,
                    'customer_group_5' => true,
                    'customer_group_6' => true,
                ],
            ],
            'oneCustomerGroupCollect' => [
                [
                    self::KEY_PRODUCT_LIST => [self::KEY_WHITE_LIST => [1]],
                ],
                [
                    'customer_group_1' => true,
                    'customer_group_2' => false,
                    'customer_group_3' => false,
                    'customer_group_4' => false,
                    'customer_group_5' => false,
                    'customer_group_6' => false,
                ],
            ],
            'noneCustomerGroupCollect' => [
                [],
                [
                    'customer_group_1' => false,
                    'customer_group_2' => false,
                    'customer_group_3' => false,
                    'customer_group_4' => false,
                    'customer_group_5' => false,
                    'customer_group_6' => false,
                ],
            ],

        ];
    }
}
