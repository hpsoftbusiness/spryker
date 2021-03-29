<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractBenefitStoreFlagMapExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group ProductAbstractMapExpander
 * @group ProductAbstractBenefitStoreFlagMapExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractBenefitStoreFlagMapExpanderPluginTest extends AbstractProductAbstractMapExpanderPluginTest
{
    private const ATTRIBUTE_BENEFIT_STORE = 'benefit_store';
    private const ATTRIBUTE_SHOPPING_POINT_STORE = 'shopping_point_store';
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';

    /**
     * @var \Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander\ProductAbstractBenefitStoreFlagMapExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractBenefitStoreFlagMapExpanderPlugin();
    }

    /**
     * @dataProvider productDataProvider
     *
     * @param array $productAttributes
     * @param bool $benefitStoreFlag
     * @param bool $shoppingPointStoreFlag
     *
     * @return void
     */
    public function testBenefitStoresFlagMappedCorrectly(
        array $productAttributes,
        bool $benefitStoreFlag,
        bool $shoppingPointStoreFlag
    ): void {
        $productData = $this->getProductDataFromAttributes($productAttributes);
        $pageMapTransfer = $this->sut->expandProductMap(
            new PageMapTransfer(),
            $this->createPageMapBuilder(),
            $productData,
            new LocaleTransfer()
        );

        self::assertEquals($benefitStoreFlag, $pageMapTransfer->getBenefitStore());
        self::assertEquals($shoppingPointStoreFlag, $pageMapTransfer->getShoppingPointStore());
    }

    /**
     * @return array[][]
     */
    public function productDataProvider(): array
    {
        return [
            'flagsSetTrueIfDataPresent' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => "5",
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 3,
                    self::ATTRIBUTE_SHOPPING_POINT_STORE => "TRUE",
                    self::ATTRIBUTE_BENEFIT_STORE => true,
                ],
                true,
                true,
            ],
            'flagsSetFalseIfDataIsAttributeFlagsFalse' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => 3,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => '2',
                    self::ATTRIBUTE_SHOPPING_POINT_STORE => false,
                    self::ATTRIBUTE_BENEFIT_STORE => 'FALSE',
                ],
                false,
                false,
            ],
            'flagsSetFalseIfDataMissing' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINT_STORE => "TRUE",
                    self::ATTRIBUTE_BENEFIT_STORE => true,
                ],
                false,
                false,
            ],
            'flagsSetFalseIfDataIsEmptyString' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => '',
                    self::ATTRIBUTE_CASHBACK_AMOUNT => '',
                    self::ATTRIBUTE_SHOPPING_POINT_STORE => "TRUE",
                    self::ATTRIBUTE_BENEFIT_STORE => true,
                ],
                false,
                false,
            ],
            'flagsSetFalseIfDataIsZeroString' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => '0',
                    self::ATTRIBUTE_CASHBACK_AMOUNT => '0',
                    self::ATTRIBUTE_SHOPPING_POINT_STORE => "TRUE",
                    self::ATTRIBUTE_BENEFIT_STORE => true,
                ],
                false,
                false,
            ],
        ];
    }
}
