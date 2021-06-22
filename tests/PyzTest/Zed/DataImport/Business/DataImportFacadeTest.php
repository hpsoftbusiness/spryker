<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Facade
 * @group DataImportFacadeTest
 * Add your own group annotations below this line
 */
class DataImportFacadeTest extends AbstractDataImportFacadeTest
{
    /**
     * @return void
     */
    public function testImportCombinedProductAbstractStore(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_ABSTRACT_STORE);

        $this->testingAbstractProductStore($this->product);
        $this->testingAbstractProductStore($this->productAffiliate);
    }

    /**
     * @return void
     */
    public function testImportCombinedProductAbstract(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_ABSTRACT);

        $this->testingAbstractProduct($this->product);
        $this->testingAbstractProduct($this->productAffiliate);
    }

    /**
     * @return void
     */
    public function testImportCombinedProductConcrete(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_CONCRETE);

        foreach ($this->product->getProductConcrete() as $productConcrete) {
            $this->testingConcreteProduct($productConcrete);
        }

        foreach ($this->productAffiliate->getProductConcrete() as $productConcrete) {
            $this->testingConcreteProduct($productConcrete);
        }
    }

    /**
     * @return void
     */
    public function testImportCombinedProductImage(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_IMAGE);

        $this->testingImage($this->product);
        $this->testingImage($this->productAffiliate);
    }

    /**
     * @return void
     */
    public function testImportCombinedProductPrice(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_PRICE);

        $this->testingProductPrice($this->product);
        $this->testingProductPrice($this->productAffiliate);
    }

    /**
     * @return void
     */
    public function testImportCombinedProductStock(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_STOCK);

        $this->testingProductStock($this->product);
        $this->testingProductStock($this->productAffiliate);
    }

    /**
     * @return void
     */
    public function testImportCombinedProductListProductConcrete(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_LIST_CONCRETE);

        foreach ($this->product->getProductConcrete() as $productConcrete) {
            $this->testingProductCombinedProductListProductConcrete($productConcrete);
        }
        foreach ($this->productAffiliate->getProductConcrete() as $productConcrete) {
            $this->testingProductCombinedProductListProductConcrete($productConcrete);
        }
    }

    /**
     * @return void
     */
    public function testImportMerchantProductOffer(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_MERCHANT_PRODUCT_OFFER);
        $this->importByDataEntity(static::DATA_ENTITY_MERCHANT_PRODUCT_OFFER_STORE);

        foreach ($this->product->getProductConcrete() as $productConcrete) {
            $this->testingMerchantProductOffer(
                $productConcrete,
                $this->product->getIsAffiliate()
            );
        }
        foreach ($this->productAffiliate->getProductConcrete() as $productConcrete) {
            $this->testingMerchantProductOffer(
                $productConcrete,
                $this->productAffiliate->getIsAffiliate()
            );
        }
    }

    /**
     * @return void
     */
    public function testImportPriceProductOffer(): void
    {
        $this->importByDataEntity(static::DATA_ENTITY_PRICE_PRODUCT_OFFER);

        foreach ($this->productAffiliate->getProductConcrete() as $concreteDataImportTransfer) {
            $this->testingPriceProductOffer($concreteDataImportTransfer);
        }
    }
}
