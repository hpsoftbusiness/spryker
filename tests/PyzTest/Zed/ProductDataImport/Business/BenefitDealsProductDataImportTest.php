<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\ProductDataImport\Business;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Shared\Config\Config;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group ProductDataImport
 * @group Business
 * @group BenefitDealsProductDataImportTest
 * Add your own group annotations below this line
 */
class BenefitDealsProductDataImportTest extends AbstractProductDataImportTest
{
    private const TEST_FILE_NAME = 'combined_product_benefit_deals.csv';
    private const ABSTRACT_SKU_BENEFIT_VOUCHER_ITEM = 'A4251740701399';
    private const CONCRETE_SKU_BENEFIT_VOUCHER_ITEM = '4251740701399';
    private const ABSTRACT_SKU_SHOPPING_POINTS_ITEM = '0011';
    private const CONCRETE_SKU_SHOPPING_POINTS_ITEM_1 = '4251740701405-0011';
    private const CONCRETE_SKU_SHOPPING_POINTS_ITEM_2 = '4251740701412-0011';

    /**
     * @return void
     */
    public function testProductImportCorrectlyImportsBenefitDealAttributes(): void
    {
        $statusCode = $this->executeProductDataImport(self::TEST_FILE_NAME);

        self::assertEquals(0, $statusCode);

        $expectedBenefitVouchersAttributes = [
            $this->getBenefitStoreAttributeKey() => true,
            $this->getBenefitAmountAttributeKey() => '10',
        ];
        $productAbstractWithBV = $this->findProductAbstractBySku(self::ABSTRACT_SKU_BENEFIT_VOUCHER_ITEM);
        self::assertNotNull($productAbstractWithBV);
        $this->assertProductAttributeValues($productAbstractWithBV->getAttributes(), $expectedBenefitVouchersAttributes);
        /**
         * @TODO-Igor Uncomment price assertions after BENEFIT price import is fixed.
         */
//        $this->assertBenefitPrice($productAbstractWithBV->getSku(), 1500);

        $productConcreteWithBV = $this->findProductConcreteBySku(self::CONCRETE_SKU_BENEFIT_VOUCHER_ITEM);
        self::assertNotNull($productConcreteWithBV);
        $this->assertProductAttributeValues($productConcreteWithBV->getAttributes(), $expectedBenefitVouchersAttributes);
//        $this->assertBenefitPrice($productConcreteWithBV->getPrices(), 1500);

        $expectedShoppingPointsAttributes = [
            $this->getShoppingPointsStoreAttributeKey() => true,
            $this->getShoppingPointsAmountAttributeKey() => '2.45',
        ];
        $productAbstractWithSP = $this->findProductAbstractBySku(self::ABSTRACT_SKU_SHOPPING_POINTS_ITEM);
        self::assertNotNull($productAbstractWithSP);
        $this->assertProductAttributeValues($productAbstractWithSP->getAttributes(), $expectedShoppingPointsAttributes);
//        $this->assertBenefitPrice($productAbstractWithSP->getSku(), 2550);

        $productConcreteWithSP_1 = $this->findProductConcreteBySku(self::CONCRETE_SKU_SHOPPING_POINTS_ITEM_1);
        self::assertNotNull($productConcreteWithSP_1);
        $this->assertProductAttributeValues($productConcreteWithSP_1->getAttributes(), $expectedShoppingPointsAttributes);
//        $this->assertBenefitPrice($productConcreteWithSP_1->getSku(), 2550);

        $productConcreteWithSP_2 = $this->findProductConcreteBySku(self::CONCRETE_SKU_SHOPPING_POINTS_ITEM_2);
        self::assertNotNull($productConcreteWithSP_2);
        $this->assertProductAttributeValues($productConcreteWithSP_2->getAttributes(), $expectedShoppingPointsAttributes);
//        $this->assertBenefitPrice($productConcreteWithSP_2->getSku(), 2550);
    }

    /**
     * @param string $sku
     * @param int|null $expectedBenefitGrossPrice
     *
     * @return void
     */
    protected function assertBenefitPrice(string $sku, ?int $expectedBenefitGrossPrice): void
    {
        $grossPrice = $this->findBenefitPriceBySku($sku);
        self::assertEquals($expectedBenefitGrossPrice, $grossPrice);
    }

    /**
     * @return string
     */
    private function getBenefitStoreAttributeKey(): string
    {
        return $this->getGlobalConfigValue(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE);
    }

    /**
     * @return string
     */
    private function getBenefitAmountAttributeKey(): string
    {
        return $this->getGlobalConfigValue(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT);
    }

    /**
     * @return string
     */
    private function getShoppingPointsStoreAttributeKey(): string
    {
        return $this->getGlobalConfigValue(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_STORE);
    }

    /**
     * @return string
     */
    private function getShoppingPointsAmountAttributeKey(): string
    {
        return $this->getGlobalConfigValue(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_AMOUNT);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    private function getGlobalConfigValue(string $key)
    {
        return Config::getInstance()->get($key);
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    private function findBenefitPriceBySku(string $sku): ?int
    {
        return $this->getPriceProductFacade()->findPriceBySku($sku, $this->getBenefitPriceTypeName());
    }

    /**
     * @return string
     */
    private function getBenefitPriceTypeName(): string
    {
        /**
         * @TODO-Igor Change facade method name when changes with BENEFIT price import are merged.
         */
        return $this->getPriceProductFacade()->getSPBenefitPriceTypeName();
    }
}
