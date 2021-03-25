<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractAffiliateMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractAffiliateMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractAffiliateMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    private const KEY_IS_AFFILIATE = 'is_affiliate';
    private const KEY_AFFILIATE_DATA = 'affiliate_data';
    private const TEST_AFFILIATE_DATA = [
        'affiliate_product' => 'TRUE',
        'affiliate_deeplink' => 'https:\/\/www.awin1.com\/pclick.php?p=24598636807&a=333885&m=14824',
        'displayed_price' => '8.00',
        'affiliate_merchant_name' => 'babymarkt DE',
        'affiliate_merchant_id' => '14824',
        'merchant_product_id' => 'A276655',
    ];

    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractAffiliateMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractAffiliateMapExpanderPlugin();
    }

    /**
     * @dataProvider productDataProvider
     *
     * @param array $productData
     * @param array $expectedMappedData
     * @param bool $isAffiliateFlag
     *
     * @return void
     */
    public function testAffiliateFlagAndDataMappedCorrectly(
        array $productData,
        array $expectedMappedData,
        bool $isAffiliateFlag
    ): void {
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        self::assertEquals($isAffiliateFlag, $pageMapTransfer->getIsAffiliate());
        foreach ($expectedMappedData as $parameterKey => $value) {
            $this->assertMappedSearchResultDataValue($pageMapTransfer, $parameterKey, $value);
        }
    }

    /**
     * @return array[][]
     */
    public function productDataProvider(): array
    {
        return [
            'affiliateFlagAndDataSet' => [
                [
                    self::KEY_IS_AFFILIATE => true,
                    self::KEY_AFFILIATE_DATA => self::TEST_AFFILIATE_DATA,
                ],
                [
                    self::KEY_IS_AFFILIATE => true,
                    self::KEY_AFFILIATE_DATA => self::TEST_AFFILIATE_DATA,
                ],
                true,
            ],
            'affiliateFlagIsFalseWhenAffiliateDataMissing' => [
                [
                    self::KEY_IS_AFFILIATE => true,
                    self::KEY_AFFILIATE_DATA => [],
                ],
                [
                    self::KEY_IS_AFFILIATE => false,
                    self::KEY_AFFILIATE_DATA => [],
                ],
                false,
            ],
        ];
    }
}
