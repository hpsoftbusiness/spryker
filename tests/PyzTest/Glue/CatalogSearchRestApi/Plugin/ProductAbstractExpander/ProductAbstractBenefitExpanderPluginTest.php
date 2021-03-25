<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Generated\Shared\Transfer\RestCatalogSearchProductBenefitTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractBenefitExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractBenefitExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractBenefitExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';

    private const VALUE_UNDEFINED = 'undefined';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractBenefitExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractBenefitExpanderPlugin();
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

        $benefitAttributeValue = $restCatalogSearchAbstractProductsTransfer->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::BENEFIT] ?? [];

        foreach ($expectedData as $key => $expectedValue) {
            if ($expectedValue === self::VALUE_UNDEFINED) {
                self::assertArrayNotHasKey($key, $benefitAttributeValue);

                continue;
            }

            self::assertEquals($expectedValue, $benefitAttributeValue[$key]);
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
                    self::ATTRIBUTE_SHOPPING_POINTS => ['3'],
                    self::ATTRIBUTE_CASHBACK_AMOUNT => [5],
                ],
                [
                    RestCatalogSearchProductBenefitTransfer::SHOPPING_POINTS => 3,
                    RestCatalogSearchProductBenefitTransfer::CASHBACK_AMOUNT => 5,
                ],
            ],
            'nonNestedValuesMappedAndTypeCasted' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => 3,
                    self::ATTRIBUTE_CASHBACK_AMOUNT => '5',
                ],
                [
                    RestCatalogSearchProductBenefitTransfer::SHOPPING_POINTS => 3,
                    RestCatalogSearchProductBenefitTransfer::CASHBACK_AMOUNT => 5,
                ],
            ],
            'nestedEmptyValuesNotMapped' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => [null],
                    self::ATTRIBUTE_CASHBACK_AMOUNT => ['0'],
                ],
                [
                    RestCatalogSearchProductBenefitTransfer::SHOPPING_POINTS => self::VALUE_UNDEFINED,
                    RestCatalogSearchProductBenefitTransfer::CASHBACK_AMOUNT => self::VALUE_UNDEFINED,
                ],
            ],
            'nonNestedEmptyValuesNotMapped' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => '0',
                    self::ATTRIBUTE_CASHBACK_AMOUNT => null,
                ],
                [
                    RestCatalogSearchProductBenefitTransfer::SHOPPING_POINTS => self::VALUE_UNDEFINED,
                    RestCatalogSearchProductBenefitTransfer::CASHBACK_AMOUNT => self::VALUE_UNDEFINED,
                ],
            ],
            'missingValuesNotMapped' => [
                [
                    self::ATTRIBUTE_SHOPPING_POINTS => '3',
                ],
                [
                    RestCatalogSearchProductBenefitTransfer::SHOPPING_POINTS => 3,
                    RestCatalogSearchProductBenefitTransfer::CASHBACK_AMOUNT => null,
                ],
            ],
        ];
    }
}
