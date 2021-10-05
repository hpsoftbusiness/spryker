<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractCustomerGroupExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CatalogSearchRestApi
 * @group Plugin
 * @group ProductAbstractExpander
 * @group ProductAbstractCustomerGroupExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractCustomerGroupExpanderPluginTest extends Unit
{
    private const ATTRIBUTE_CUSTOMER_GROUP = 'customer_group';

    /**
     * @var \PyzTest\Glue\CatalogSearchRestApi\CatalogSearchRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractCustomerGroupExpanderPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new ProductAbstractCustomerGroupExpanderPlugin();
    }

    /**
     * @dataProvider productAttributesProvider
     *
     * @param array $attributeData
     * @param array $expectedCustomerGroup
     *
     * @return void
     */
    public function testProductAttributesMappedWithCorrectTypes(
        array $attributeData,
        array $expectedCustomerGroup
    ): void {
        $restCatalogSearchAbstractProductsTransfer = new RestCatalogSearchAbstractProductsTransfer();
        $this->sut->expand($attributeData, $restCatalogSearchAbstractProductsTransfer);

        $customerGroups = $restCatalogSearchAbstractProductsTransfer
                ->toArrayRecursiveCamelCased()[RestCatalogSearchAbstractProductsTransfer::CUSTOMER_GROUP] ?? [];

        self::assertEquals($expectedCustomerGroup, $customerGroups);
    }

    /**
     * @return array
     */
    public function productAttributesProvider(): array
    {
        return [
            'allGroupsExist' => [
                [
                    self::ATTRIBUTE_CUSTOMER_GROUP => [
                        'customer_group_1' => true,
                        'customer_group_2' => true,
                        'customer_group_3' => true,
                        'customer_group_4' => true,
                        'customer_group_5' => true,
                    ],
                ],
                [
                    'customer_group_1' => true,
                    'customer_group_2' => true,
                    'customer_group_3' => true,
                    'customer_group_4' => true,
                    'customer_group_5' => true,
                ],
            ],
            'allGroupsExistOneIsTrue' => [
                [
                    self::ATTRIBUTE_CUSTOMER_GROUP => [
                        'customer_group_1' => true,
                        'customer_group_2' => false,
                        'customer_group_3' => false,
                        'customer_group_4' => false,
                        'customer_group_5' => false,
                    ],
                ],
                [
                    'customer_group_1' => true,
                    'customer_group_2' => false,
                    'customer_group_3' => false,
                    'customer_group_4' => false,
                    'customer_group_5' => false,
                ],
            ],
            'oneGroupExist' => [
                [
                    self::ATTRIBUTE_CUSTOMER_GROUP => [
                        'customer_group_1' => true,
                    ],
                ],
                [
                    'customer_group_1' => true,
                ],
            ],
            'withoutGroups' => [
                [],
                [],
            ],
        ];
    }
}
