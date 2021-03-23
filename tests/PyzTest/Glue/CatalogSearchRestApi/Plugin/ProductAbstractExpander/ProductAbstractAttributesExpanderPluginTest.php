<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractAttributesExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractAttributesExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractAttributesExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_BRAND = 'brand';
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractAttributesExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractAttributesExpanderPlugin();
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

        $mappedAttributesData = $restCatalogSearchAbstractProductsTransfer->toArray();
        foreach ($expectedData as $key => $expectedValue) {
            self::assertEquals($expectedValue, $mappedAttributesData[$key]);
        }
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'nestedValuesMappedAndTypeCasted' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => [ '3' ],
                    self::ATTRIBUTE_CASHBACK_AMOUNT => [ 5 ],
                    self::ATTRIBUTE_BRAND => [ 'Samsung' ],
                ],
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => 3,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 5,
                    self::ATTRIBUTE_BRAND => 'Samsung',
                ],
            ],
            'nonNestedValuesMappedAndTypeCasted' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => 3,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => '5',
                    self::ATTRIBUTE_BRAND => '',
                ],
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => 3,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => 5,
                    self::ATTRIBUTE_BRAND => '',
                ],
            ],
            'nestedEmptyValuesNotMapped' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => [ null ],
                    self::ATTRIBUTE_CASHBACK_AMOUNT => [ '0' ],
                    self::ATTRIBUTE_BRAND => [ null ],
                ],
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => null,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => null,
                    self::ATTRIBUTE_BRAND => null,
                ],
            ],
            'nonNestedEmptyValuesNotMapped' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => '0',
                    self::ATTRIBUTE_CASHBACK_AMOUNT => null,
                    self::ATTRIBUTE_BRAND => null,
                ],
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => null,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => null,
                    self::ATTRIBUTE_BRAND => null,
                ],
            ],
            'missingValuesNotMapped' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => '3',
                ],
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => 3,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => null,
                    self::ATTRIBUTE_BRAND => null,
                ],
            ],
        ];
    }
}
