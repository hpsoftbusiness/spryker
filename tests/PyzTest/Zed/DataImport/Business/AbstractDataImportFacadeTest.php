<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\TestProductAbstractDataImportTransfer;
use Generated\Shared\Transfer\TestProductConcreteDataImportTransfer;
use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\Stock\Business\StockFacadeInterface;
use PyzTest\Zed\DataImport\_support\DataProvider;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface;
use Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Facade
 * @group AbstractDataImportFacadeTest
 * Add your own group annotations below this line
 */
abstract class AbstractDataImportFacadeTest extends Unit
{
    protected const EN_LOCAL = 'en_US';
    protected const DE_LOCAL = 'de_DE';

    protected const SOURCE = __DIR__ . '/../../../../../data/import/common/DE/combined_product_local.csv';
    protected const SOURCE_PRODUCT_OFFER = __DIR__ . '/../../../../../data/import/common/common/marketplace/merchant_product_offer.csv';
    protected const SOURCE_PRODUCT_OFFER_STORE = __DIR__ . '/../../../../../data/import/common/common/marketplace/merchant_product_offer_store.csv';
    protected const SOURCE_PRICE_PRODUCT_OFFER = __DIR__ . '/../../../../../data/import/common/common/marketplace/price_product_offer.csv';
    protected const DATA_ENTITY_ABSTRACT = 'combined-product-abstract';
    protected const DATA_ENTITY_ABSTRACT_STORE = 'combined-product-abstract-store';
    protected const DATA_ENTITY_CONCRETE = 'combined-product-concrete';
    protected const DATA_ENTITY_IMAGE = 'combined-product-image';
    protected const DATA_ENTITY_PRICE = 'combined-product-price';
    protected const DATA_ENTITY_STOCK = 'combined-product-stock';
    protected const DATA_ENTITY_LIST_CONCRETE = 'combined-product-list-product-concrete';
    protected const DATA_ENTITY_MERCHANT_PRODUCT_OFFER = 'merchant-product-offer';
    protected const DATA_ENTITY_MERCHANT_PRODUCT_OFFER_STORE = 'merchant-product-offer-store';
    protected const DATA_ENTITY_PRICE_PRODUCT_OFFER = 'price-product-offer';

    /**
     * @var \PyzTest\Zed\DataImport\DataImportTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer
     */
    protected $product;

    /**
     * @var \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer
     */
    protected $productAffiliate;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $dataProvider = new DataProvider();
        $this->product = $dataProvider->getProduct();
        $this->productAffiliate = $dataProvider->getProductAffiliate();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\DataImportFacadeInterface
     */
    protected function getDataImportFacade(): DataImportFacadeInterface
    {
        return $this->tester->getLocator()->dataImport()->facade();
    }

    /**
     * @return \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    protected function getProductFacade(): ProductFacadeInterface
    {
        return $this->tester->getLocator()->product()->facade();
    }

    /**
     * @return \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->tester->getLocator()->priceProduct()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected function getProductImageFacade(): ProductImageFacadeInterface
    {
        return $this->tester->getLocator()->productImage()->facade();
    }

    /**
     * @return \Pyz\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->tester->getLocator()->stock()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    protected function getProductListFacade(): ProductListFacadeInterface
    {
        return $this->tester->getLocator()->productList()->facade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface
     */
    protected function getProductOfferFacade(): ProductOfferFacadeInterface
    {
        return $this->tester->getLocator()->productOffer()->facade();
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected function getMerchantFacade(): MerchantFacadeInterface
    {
        return $this->tester->getLocator()->merchant()->facade();
    }

    /**
     * @return \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface
     */
    protected function getPriceProductOfferFacade(): PriceProductOfferFacadeInterface
    {
        return $this->tester->getLocator()->priceProductOffer()->facade();
    }

    /**
     * @param string $dataEntity
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    protected function getDataImportConfigurationActionTransfer(
        string $dataEntity
    ): DataImportConfigurationActionTransfer {
        $dataImportConfigurationActionTransfer = new DataImportConfigurationActionTransfer();

        switch ($dataEntity) {
            case self::DATA_ENTITY_MERCHANT_PRODUCT_OFFER:
                $dataImportConfigurationActionTransfer->setSource(static::SOURCE_PRODUCT_OFFER);
                break;
            case self::DATA_ENTITY_MERCHANT_PRODUCT_OFFER_STORE:
                $dataImportConfigurationActionTransfer->setSource(static::SOURCE_PRODUCT_OFFER_STORE);
                break;
            case self::DATA_ENTITY_PRICE_PRODUCT_OFFER:
                $dataImportConfigurationActionTransfer->setSource(static::SOURCE_PRICE_PRODUCT_OFFER);
                break;
            default:
                $dataImportConfigurationActionTransfer->setSource(static::SOURCE);
        }
        $dataImportConfigurationActionTransfer->setDataEntity($dataEntity);

        return $dataImportConfigurationActionTransfer;
    }

    /**
     * @param string $dataEntity
     *
     * @return void
     */
    protected function importByDataEntity(string $dataEntity): void
    {
        $this->getDataImportFacade()->importByAction($this->getDataImportConfigurationActionTransfer($dataEntity));
    }

    /**
     * @param string $conditionName
     * @param string $expected
     * @param string $received
     *
     * @return string
     */
    protected function generateAssertMessage(string $conditionName, string $expected, string $received): string
    {
        return sprintf(
            'Tested "%s":
            - expected: %s
            - received: %s.
             Please note, "RECEIVED" are getting from files "data/import/..."
             "EXPECTED" from "tests/PyzTest/Zed/DataImport/_support/DataProvider.php"',
            $conditionName,
            $expected,
            $received
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer $testedProduct
     *
     * @return void
     */
    protected function testingAbstractProductStore(TestProductAbstractDataImportTransfer $testedProduct): void
    {
        $store = $this->getStoreFacade()->findStoreByName($testedProduct->getStore());
        $this->assertNotNull($store);
        $productAbstract = $this->getProductAbstractBySku($testedProduct->getSku());
        $storeIds = $productAbstract->getStoreRelation()->getIdStores();
        $this->assertTrue(in_array($store->getIdStore(), $storeIds));
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer $testedProduct
     *
     * @return void
     */
    protected function testingAbstractProduct(TestProductAbstractDataImportTransfer $testedProduct): void
    {
        $this->assertTrue($this->getProductFacade()->hasProductAbstract($testedProduct->getSku()));

        $productAbstract = $this->getProductAbstractBySku($testedProduct->getSku());

        $this->assertTrue(
            $testedProduct->getIsAffiliate() === $productAbstract->getIsAffiliate(),
            $this->generateAssertMessage(
                'Abstract product isAffiliate',
                (string)$testedProduct->getIsAffiliate(),
                (string)$productAbstract->getIsAffiliate()
            )
        );

        foreach ($productAbstract->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getName() === self::EN_LOCAL) {
                $this->assertTrue(
                    $localizedAttribute->getName() === $testedProduct->getNameEN(),
                    $this->generateAssertMessage(
                        'Abstract product name EN',
                        (string)$testedProduct->getNameEN(),
                        (string)$localizedAttribute->getName()
                    )
                );
            } elseif ($localizedAttribute->getLocale()->getName() === self::DE_LOCAL) {
                $this->assertTrue(
                    $localizedAttribute->getName() === $testedProduct->getNameDE(),
                    $this->generateAssertMessage(
                        'Abstract product name DE',
                        (string)$testedProduct->getNameDE(),
                        (string)$localizedAttribute->getName()
                    )
                );
            }
        }

        $this->testingAttributes($productAbstract->getAttributes(), $testedProduct->getAttribute());

        if ($testedProduct->getAttributeAffiliate() !== null) {
            $this->testingAttributes(
                json_decode($productAbstract->getAffiliateData(), true),
                $testedProduct->getAttributeAffiliate()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer $testedProductConcrete
     *
     * @return void
     */
    protected function testingConcreteProduct(TestProductConcreteDataImportTransfer $testedProductConcrete): void
    {
        $this->assertTrue($this->getProductFacade()->hasProductConcrete($testedProductConcrete->getSku()));

        $productConcrete = $this->getProductConcreteBySku($testedProductConcrete->getSku());

        $this->assertTrue(
            $testedProductConcrete->getIsActive() === $productConcrete->getIsActive(),
            $this->generateAssertMessage(
                'Product concrete isActive',
                (string)$testedProductConcrete->getIsActive(),
                (string)$productConcrete->getIsActive()
            )
        );
        foreach ($productConcrete->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getName() === self::EN_LOCAL) {
                $this->assertTrue(
                    $localizedAttribute->getName() === $testedProductConcrete->getNameEN(),
                    $this->generateAssertMessage(
                        'Abstract product name EN',
                        (string)$testedProductConcrete->getNameEN(),
                        (string)$localizedAttribute->getName()
                    )
                );
            } elseif ($localizedAttribute->getLocale()->getName() === self::DE_LOCAL) {
                $this->assertTrue(
                    $localizedAttribute->getName() === $testedProductConcrete->getNameDE(),
                    $this->generateAssertMessage(
                        'Abstract product name DE',
                        (string)$testedProductConcrete->getNameDE(),
                        (string)$localizedAttribute->getName()
                    )
                );
            }
        }

        $this->testingAttributes($productConcrete->getAttributes(), $testedProductConcrete->getAttribute());
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer $testedProduct
     *
     * @return void
     */
    protected function testingImage(TestProductAbstractDataImportTransfer $testedProduct): void
    {
        $productAbstract = $this->getProductAbstractBySku($testedProduct->getSku());

        $this->assertNotEmpty(
            $this->getProductImageFacade()->getCombinedAbstractImageSets(
                $productAbstract->getIdProductAbstract(),
                null
            )
        );
        foreach ($testedProduct->getProductConcrete() as $productConcrete) {
            $this->testingConcreteImage($productConcrete);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer $testedProduct
     *
     * @return void
     */
    protected function testingProductPrice(TestProductAbstractDataImportTransfer $testedProduct): void
    {
        $productPrices = $this->getPriceProductFacade()->findPricesBySkuForCurrentStore($testedProduct->getSku());
        $this->testingPrice((new ArrayObject($productPrices)), $testedProduct->getPrice(), $testedProduct->getSku());

        foreach ($testedProduct->getProductConcrete() as $testedProductConcrete) {
            $productPrices = $this->getPriceProductFacade()->findPricesBySkuForCurrentStore(
                $testedProductConcrete->getSku()
            );
            $this->testingPrice(
                (new ArrayObject($productPrices)),
                $testedProductConcrete->getPrice(),
                $testedProductConcrete->getSku()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductAbstractDataImportTransfer $testedProduct
     *
     * @return void
     */
    protected function testingProductStock(TestProductAbstractDataImportTransfer $testedProduct): void
    {
        foreach ($testedProduct->getProductConcrete() as $testedProductConcrete) {
            $productConcrete = $this->getProductConcreteBySku($testedProductConcrete->getSku());
            $stockProducts = $this->getStockFacade()->getStockProductsByIdProduct(
                $productConcrete->getIdProductConcrete()
            );
            $this->assertTrue(count($stockProducts) > 0);

            $this->assertTrue(
                $testedProductConcrete->getStock()->getIsNeverOutOfStock() === $stockProducts[0]->getIsNeverOutOfStock(
                ),
                $this->generateAssertMessage(
                    'Product stock "IsNeverOutOfStock"',
                    $testedProductConcrete->getStock()->getIsNeverOutOfStock(),
                    $stockProducts[0]->getIsNeverOutOfStock()
                )
            );

            $testedStockQuantity = $testedProductConcrete->getStock()->getQuantity();
            $stockQuantity = $stockProducts[0]->getQuantity()->toInt();
            $this->assertTrue(
                $testedStockQuantity === $stockQuantity,
                $this->generateAssertMessage(
                    'Product stock "Quantity"',
                    $testedStockQuantity,
                    $stockQuantity
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer $testedProductConcrete
     *
     * @return void
     */
    protected function testingProductCombinedProductListProductConcrete(
        TestProductConcreteDataImportTransfer $testedProductConcrete
    ): void {
        foreach ($testedProductConcrete->getAttribute() as $attribute) {
            if (strpos($attribute->getName(), 'customer_group') !== false && $attribute->getValue()) {
                $productConcrete = $this->getProductConcreteBySku($testedProductConcrete->getSku());
                $productListIds = $this->getProductListFacade()->getProductWhitelistIdsByIdProduct(
                    $productConcrete->getIdProductConcrete()
                );
                $productListsKey = array_map(
                    function (int $productListId) {
                        $productList = $this->getProductListFacade()->getProductListById(
                            (new ProductListTransfer())->setIdProductList($productListId)
                        );

                        return $productList->getKey();
                    },
                    $productListIds
                );
                $this->assertArrayHasKey(
                    $attribute->getName(),
                    array_flip($productListsKey),
                    $this->generateAssertMessage(
                        'Product concrete in Product list',
                        $attribute->getName(),
                        implode(', ', $productListsKey)
                    )
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer $testedProductConcrete
     * @param bool $isAffiliate
     *
     * @return void
     */
    protected function testingMerchantProductOffer(
        TestProductConcreteDataImportTransfer $testedProductConcrete,
        bool $isAffiliate
    ) {
        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setConcreteSku($testedProductConcrete->getSku());
        $productOffers = $this->getProductOfferFacade()->find($productOfferCriteriaFilterTransfer);

        if ($isAffiliate) {
            $this->assertCount(count($testedProductConcrete->getOffer()), $productOffers->getProductOffers());

            foreach ($productOffers->getProductOffers() as $productOffer) {
                $this->testingOffer(
                    $productOffer,
                    $testedProductConcrete->getOffer(),
                    $testedProductConcrete->getSku()
                );
            }
        } else {
            $this->assertEmpty($productOffers->getProductOffers());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer $testedProductConcrete
     *
     * @return void
     */
    protected function testingPriceProductOffer(TestProductConcreteDataImportTransfer $testedProductConcrete): void
    {
        foreach ($testedProductConcrete->getOffer() as $testedOffer) {
            $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
            $merchantCriteriaTransfer->setMerchantReference($testedOffer->getMerchantReference());
            $merchant = $this->getMerchantFacade()->findOne($merchantCriteriaTransfer);
            $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
            $productOfferCriteriaFilterTransfer->setConcreteSku($testedProductConcrete->getSku());
            $productOfferCriteriaFilterTransfer->setMerchantIds([$merchant->getIdMerchant()]);
            $productOffer = $this->getProductOfferFacade()->findOne($productOfferCriteriaFilterTransfer);
            $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
            $priceProductOfferCriteriaTransfer->setIdProductOffer($productOffer->getIdProductOffer());
            $productPrices = $this->getPriceProductOfferFacade()->getProductOfferPrices(
                $priceProductOfferCriteriaTransfer
            );
            $this->testingPrice($productPrices, $testedOffer->getPrice(), $testedProductConcrete->getSku());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOffer
     * @param \ArrayObject|\Generated\Shared\Transfer\TestOfferDataImportTransfer[] $testedOffers
     * @param string $testedConcreteSku
     *
     * @return void
     */
    private function testingOffer(
        ProductOfferTransfer $productOffer,
        ArrayObject $testedOffers,
        string $testedConcreteSku
    ): void {
        $this->assertTrue($productOffer->getIsActive());
        $correctTestedOffer = null;

        foreach ($testedOffers as $testedOffer) {
            $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
            $merchantCriteriaTransfer->setMerchantReference($testedOffer->getMerchantReference());
            $merchant = $this->getMerchantFacade()->findOne($merchantCriteriaTransfer);

            if ($merchant->getIdMerchant() === $productOffer->getFkMerchant()
                && $productOffer->getConcreteSku() === $testedConcreteSku) {
                $correctTestedOffer = $testedOffer;
                break;
            }
        }
        if ($correctTestedOffer !== null) {
            $this->assertTrue(
                $correctTestedOffer->getMerchantSku() === $productOffer->getMerchantSku(),
                $this->generateAssertMessage(
                    'Merchant SKU',
                    $correctTestedOffer->getMerchantSku(),
                    $productOffer->getMerchantSku()
                )
            );
            $hasStore = false;
            foreach ($productOffer->getStores() as $storeTransfer) {
                if ($storeTransfer->getName() === $correctTestedOffer->getStore()) {
                    $hasStore = true;
                    break;
                }
            }

            $this->assertTrue(
                $hasStore,
                $this->generateAssertMessage('Offer store', (string)$hasStore, (string)!$hasStore)
            );

            /** @var string $affiliateData */
            $affiliateData = $productOffer->getAffiliateData();
            $affiliateData = json_decode($affiliateData, true);
            /** @var array $affiliateData */
            foreach ($affiliateData as $key => $value) {
                $hasAttribute = false;
                $testedAttributeValue = null;
                foreach ($correctTestedOffer->getAffiliateData() as $testedAffiliateDatum) {
                    if ($testedAffiliateDatum->getName() === $key) {
                        $hasAttribute = true;
                        $testedAttributeValue = $testedAffiliateDatum->getValue();
                        break;
                    }
                }
                $this->assertTrue($hasAttribute);
                $this->assertTrue(
                    $testedAttributeValue === $value,
                    $this->generateAssertMessage('Affiliate data value', $testedAttributeValue, $value)
                );
            }
        }
    }

    /**
     * @param array $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\TestAttributeDataImportTransfer[] $testedAttributes
     *
     * @return void
     */
    private function testingAttributes(array $attributes, ArrayObject $testedAttributes): void
    {
        foreach ($testedAttributes as $testedAttribute) {
            $this->assertContains(
                $testedAttribute->getName(),
                array_keys($attributes),
                $this->generateAssertMessage(
                    'Contain attribute key',
                    (string)$testedAttribute->getName(),
                    implode(', ', array_keys($attributes))
                )
            );

            $this->assertTrue(
                $testedAttribute->getValue() === $attributes[$testedAttribute->getName()],
                $this->generateAssertMessage(
                    'Attribute value - ' . $testedAttribute->getName(),
                    (string)$testedAttribute->getValue(),
                    (string)$attributes[$testedAttribute->getName()]
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TestProductConcreteDataImportTransfer $testedProductConcrete
     *
     * @return void
     */
    private function testingConcreteImage(TestProductConcreteDataImportTransfer $testedProductConcrete): void
    {
        $productConcrete = $this->getProductConcreteBySku($testedProductConcrete->getSku());

        $productImageSet = $this->getProductImageFacade()->getCombinedConcreteImageSets(
            $productConcrete->getIdProductConcrete(),
            null,
            null
        );
        $this->assertCount(1, $productImageSet);

        foreach ($productImageSet as $productImages) {
            foreach ($productImages->getProductImages() as $productImage) {
                $this->assertTrue(
                    $testedProductConcrete->getImage()->getUrlLarge() === $productImage->getExternalUrlLarge(),
                    $this->generateAssertMessage(
                        'Concrete image large',
                        (string)$testedProductConcrete->getImage()->getUrlLarge(),
                        (string)$productImage->getExternalUrlLarge()
                    )
                );
                $this->assertTrue(
                    $testedProductConcrete->getImage()->getUrlSmall() === $productImage->getExternalUrlSmall(),
                    $this->generateAssertMessage(
                        'Concrete image small',
                        (string)$testedProductConcrete->getImage()->getUrlSmall(),
                        (string)$productImage->getExternalUrlSmall()
                    )
                );
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $productPrices
     * @param \ArrayObject|\Generated\Shared\Transfer\TestPriceDataImportTransfer[] $prices
     * @param string $sku
     *
     * @return void
     */
    private function testingPrice(ArrayObject $productPrices, ArrayObject $prices, string $sku): void
    {
        foreach ($prices as $price) {
            $productPriceByType = null;
            foreach ($productPrices as $productPrice) {
                if ($productPrice->getPriceType()->getName() === $price->getType()) {
                    $productPriceByType = $productPrice;
                }
            }
            $this->assertNotNull(
                $productPriceByType,
                $this->generateAssertMessage(
                    sprintf('Price product type "%s" exist for sku %s', $price->getType(), $sku),
                    (string)true,
                    (string)false
                )
            );
            if ($productPriceByType !== null) {
                $productCurrency = $productPriceByType->getMoneyValue()->getCurrency()->getCode();
                $productGrossValue = $productPriceByType->getMoneyValue()->getGrossAmount();
                $productNetValue = $productPriceByType->getMoneyValue()->getNetAmount();
                $this->assertTrue(
                    $price->getCurrency() === $productCurrency,
                    $this->generateAssertMessage(
                        sprintf('Product currency: for sku - %s, type - %s', $sku, $price->getType()),
                        (string)$price->getCurrency(),
                        (string)$productCurrency
                    )
                );
                $this->assertTrue(
                    $price->getValueNet() === $productNetValue,
                    $this->generateAssertMessage(
                        sprintf('Product Net value: for sku - %s, type - %s', $sku, $price->getType()),
                        (string)$price->getValueNet(),
                        (string)$productNetValue
                    )
                );
                $this->assertTrue(
                    $price->getValueGross() === $productGrossValue,
                    $this->generateAssertMessage(
                        sprintf('Product Gross value: for sku - %s, type - %s', $sku, $price->getType()),
                        (string)$price->getValueGross(),
                        (string)$productGrossValue
                    )
                );
            }
        }
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    private function getProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        return $this->getProductFacade()->findProductConcreteById(
            $this->getProductFacade()->findProductConcreteIdBySku($sku)
        );
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    private function getProductAbstractBySku(string $sku): ?ProductAbstractTransfer
    {
        return $this->getProductFacade()->findProductAbstractById(
            $this->getProductFacade()->findProductAbstractIdBySku($sku)
        );
    }
}
