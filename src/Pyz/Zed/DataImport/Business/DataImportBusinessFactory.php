<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\DataImport\Business\CombinedProduct\Product\CombinedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\Product\CombinedProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\CombinedProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\CombinedProductAbstractTypeDataSetCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\Writer\CombinedProductAbstractBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\Writer\CombinedProductAbstractPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreDataSetCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer\CombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer\CombinedProductAbstractStorePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\CombinedProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\CombinedProductConcreteTypeDataSetCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer\CombinedProductConcreteBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer\CombinedProductConcretePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductGroup\CombinedProductGroupMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\Writer\CombinedProductImageBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\Writer\CombinedProductImagePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\CombinedProductListProductConcreteAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\CombinedProductListProductConcreteMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\CombinedProductListProductConcreteWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\CombinedProductListProductConcreteBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql\ProductListProductConcreteMariaDbSql;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql\ProductListProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\CombinedProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\CombinedProductPriceMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\Writer\CombinedProductPriceBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\Writer\CombinedProductPricePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockTypeDataSetCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\Writer\CombinedProductStockBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\Writer\CombinedProductStockPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\CategoryTemplate\CategoryTemplateWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlock\CmsBlockWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockStore\CmsBlockStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsTemplate\CmsTemplateWriterStep;
use Pyz\Zed\DataImport\Business\Model\Country\Repository\CountryRepository;
use Pyz\Zed\DataImport\Business\Model\Currency\CurrencyWriterStep;
use Pyz\Zed\DataImport\Business\Model\Customer\CustomerWriterStep;
use Pyz\Zed\DataImport\Business\Model\CustomerGroup\CustomerGroupWriterStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatter;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\DataImporterConditional;
use Pyz\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareConditional;
use Pyz\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Pyz\Zed\DataImport\Business\Model\Discount\DiscountWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountAmount\DiscountAmountWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountStore\DiscountStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountVoucher\DiscountVoucherWriterStep;
use Pyz\Zed\DataImport\Business\Model\GiftCard\GiftCardAbstractConfigurationWriterStep;
use Pyz\Zed\DataImport\Business\Model\GiftCard\GiftCardConcreteConfigurationWriterStep;
use Pyz\Zed\DataImport\Business\Model\Glossary\GlossaryWriterStep;
use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Pyz\Zed\DataImport\Business\Model\Locale\LocaleNameToIdLocaleStep;
use Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepository;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\AffiliateDataStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\ConcreteSkuValidationStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\DisableMerchantProductOffersBeforeImport;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\DisableProductsWithoutOfferAfterImport;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\MerchantReferenceToIdMerchantStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\ProductOfferReferenceToIdProductOfferStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\StoreNameToIdStoreStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer\MerchantProductOfferBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer\MerchantProductOfferStoreBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer\MerchantProductOfferStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer\MerchantProductOfferWriterStep;
use Pyz\Zed\DataImport\Business\Model\Navigation\NavigationKeyToIdNavigationStep;
use Pyz\Zed\DataImport\Business\Model\Navigation\NavigationWriterStep;
use Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeValidityDatesStep;
use Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeWriterStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\CurrencyToIdCurrencyStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\PreparePriceDataStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\PriceProductWriterStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\PriceTypeToIdPriceTypeStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\ProductOfferReferenceToProductOfferDataStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\ProductOfferToIdProductStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\StoreToIdStoreStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer\PriceProductOfferBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer\PriceProductOfferWriterStep;
use Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer\PriceProductStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddCategoryKeysStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddProductAbstractSkusStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractCheckExistenceStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractSkuToIdProductAbstractStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStorePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\AddProductAttributeKeysStep;
use Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\ProductAttributeKeyWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteCheckExistenceStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductSkuToIdProductStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcretePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\ProductGroupBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\ProductGroupWriter;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql\ProductGroupMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql\ProductGroupSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepository;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImagePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductListCustomerGroup\ProductListCustomerGroupWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementAttributeWriter;
use Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductOption\ProductOptionWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductOptionPrice\ProductOptionPriceWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductReview\ProductReviewWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\Hook\ProductSearchAfterImportHook;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\ProductSearchAttributeWriter;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttributeMap\ProductSearchAttributeMapWriter;
use Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetImageExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Hook\ProductStockAfterImportPublishHook;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReader;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutor;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Pyz\Zed\DataImport\Business\Model\Store\StoreReader;
use Pyz\Zed\DataImport\Business\Model\Store\StoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\Tax\TaxSetNameToIdTaxSetStep;
use Pyz\Zed\DataImport\Business\Model\Tax\TaxWriterStep;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductAbstract\CombinedProductAbstractBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductConcrete\CombinedProductConcreteBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductGroup\CombinedProductGroupBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductImage\CombinedProductImageBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductListProductConcrete\CombinedProductListProductConcreteBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductPrice\CombinedProductPriceBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductStock\CombinedProductStockBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\MerchantProductOffer\MerchantProductOfferBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\MerchantProductOffer\MerchantProductOfferStoreBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\MerchantProductOffer\PriceProductOfferBulkPdoMariaDbWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract\ProductAbstractPropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstractStore\ProductAbstractStorePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductConcrete\ProductConcretePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductImage\ProductImagePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductStock\ProductStockPropelWriterPlugin;
use Pyz\Zed\DataImport\DataImportConfig;
use Pyz\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory as SprykerDataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterCollection;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @method \Pyz\Zed\DataImport\DataImportConfig getConfig()
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class DataImportBusinessFactory extends SprykerDataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getDataImporterByType(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?DataImporterInterface {
        switch ($dataImportConfigurationActionTransfer->getDataEntity()) {
            case DataImportConfig::IMPORT_TYPE_STORE:
                return $this->createStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CURRENCY:
                return $this->createCurrencyImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CATEGORY_TEMPLATE:
                return $this->createCategoryTemplateImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CUSTOMER_GROUP:
                return $this->createCustomerGroupImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CUSTOMER:
                return $this->createCustomerImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_GLOSSARY:
                return $this->createGlossaryImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_TAX:
                return $this->createTaxImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT:
                return $this->createDiscountImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT_STORE:
                return $this->createDiscountStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT_VOUCHER:
                return $this->createDiscountVoucherImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY:
                return $this->createProductAttributeKeyImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE:
                return $this->createProductManagementAttributeImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_ABSTRACT:
                return $this->createProductAbstractImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_ABSTRACT_STORE:
                return $this->createProductAbstractStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_CONCRETE:
                return $this->createProductConcreteImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_IMAGE:
                return $this->createProductImageImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_OPTION:
                return $this->createProductOptionImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_OPTION_PRICE:
                return $this->createProductOptionPriceImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_GROUP:
                return $this->createProductGroupImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_ABSTRACT:
                return $this->createCombinedProductAbstractImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_ABSTRACT_STORE:
                return $this->createCombinedProductAbstractStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_CONCRETE:
                return $this->createCombinedProductConcreteImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_IMAGE:
                return $this->createCombinedProductImageImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_PRICE:
                return $this->createCombinedProductPriceImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_STOCK:
                return $this->createCombinedProductStockImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_GROUP:
                return $this->getCombinedProductGroupImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_LIST_PRODUCT_CONCRETE:
                return $this->getCombinedProductListProductConcreteImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_LIST_CUSTOMER_GROUP:
                return $this->createProductListCustomerGroupImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_REVIEW:
                return $this->createProductReviewImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_SET:
                return $this->createProductSetImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP:
                return $this->createProductSearchAttributeMapImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE:
                return $this->createProductSearchAttributeImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_TEMPLATE:
                return $this->createCmsTemplateImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_BLOCK:
                return $this->createCmsBlockImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_BLOCK_STORE:
                return $this->createCmsBlockStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT_AMOUNT:
                return $this->createDiscountAmountImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION:
                return $this->createAbstractGiftCardConfigurationImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION:
                return $this->createConcreteGiftCardConfigurationImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_STOCK:
                return $this->createProductStockImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_NAVIGATION:
                return $this->createNavigationImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_NAVIGATION_NODE:
                return $this->createNavigationNodeImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER:
                return $this->getMerchantProductOfferImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER_STORE:
                return $this->getMerchantProductOfferStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRICE_PRODUCT_OFFER:
                return $this->getPriceProductOfferImporter($dataImportConfigurationActionTransfer);
            default:
                return null;
        }
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterConditional
     */
    public function createDataImporterConditional($importType, DataReaderInterface $reader)
    {
        return new DataImporterConditional($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCurrencyImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CurrencyWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createStoreImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->createDataImporter(
            $dataImportConfigurationActionTransfer->getDataEntity(),
            new StoreReader(
                $this->createDataSet(
                    Store::getInstance()->getAllowedStores()
                )
            )
        );

        $dataSetStepBroker = $this->createDataSetStepBroker();
        $dataSetStepBroker->addStep(new StoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createGlossaryImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(GlossaryWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createLocaleNameToIdStep(GlossaryWriterStep::KEY_LOCALE))
            ->addStep(new GlossaryWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCategoryTemplateImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): DataImporterInterface {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CategoryTemplateWriterStep());

        $dataImporter
            ->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCustomerImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CustomerWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCustomerGroupImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CustomerGroupWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductListCustomerGroupImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new ProductListCustomerGroupWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsTemplateImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsTemplateWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsBlockImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsBlockWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep(
                $this->createLocalizedAttributesExtractorStep(
                    [
                        CmsBlockWriterStep::KEY_PLACEHOLDER_TITLE,
                        CmsBlockWriterStep::KEY_PLACEHOLDER_DESCRIPTION,
                        CmsBlockWriterStep::KEY_PLACEHOLDER_CONTENT,
                        CmsBlockWriterStep::KEY_PLACEHOLDER_LINK,
                    ]
                )
            )
            ->addStep(new CmsBlockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsBlockStoreImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsBlockStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker->addStep(new CmsBlockStoreWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountStoreImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountStoreWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountAmountImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountAmountWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountAmountWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountVoucherImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountVoucherWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountVoucherWriterStep($this->createDiscountConfig()));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\Discount\DiscountConfig
     */
    protected function createDiscountConfig()
    {
        return new DiscountConfig();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductOptionImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(ProductOptionWriterStep::KEY_TAX_SET_NAME))
            ->addStep(
                $this->createLocalizedAttributesExtractorStep(
                    [
                        ProductOptionWriterStep::KEY_GROUP_NAME,
                        ProductOptionWriterStep::KEY_OPTION_NAME,
                    ]
                )
            )
            ->addStep(new ProductOptionWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductOptionPriceImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new ProductOptionPriceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductStockImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductStockHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductStockHydratorStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductStockDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Hook\ProductStockAfterImportPublishHook
     */
    protected function createProductStockAfterImportPublishHook()
    {
        return new ProductStockAfterImportPublishHook();
    }

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected function getProductBundleFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductImageImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductImageHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(
                $this->createProductAbstractSkuToIdProductAbstractStep(
                    ProductImageHydratorStep::COLUMN_ABSTRACT_SKU,
                    ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT
                )
            )
            ->addStep(
                $this->createProductSkuToIdProductStep(
                    ProductImageHydratorStep::COLUMN_CONCRETE_SKU,
                    ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT
                )
            )
            ->addStep(
                $this->createLocaleNameToIdStep(
                    ProductImageHydratorStep::COLUMN_LOCALE,
                    ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE
                )
            )
            ->addStep(new ProductImageHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductImageDataWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepositoryInterface
     */
    protected function createLocaleRepository()
    {
        return new LocaleRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createTaxImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(TaxWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new TaxWriterStep($this->createCountryRepository()));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Country\Repository\CountryRepositoryInterface
     */
    protected function createCountryRepository()
    {
        return new CountryRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createNavigationImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(NavigationWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new NavigationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createNavigationNodeImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(NavigationNodeWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createNavigationKeyToIdNavigationStep(NavigationNodeWriterStep::KEY_NAVIGATION_KEY))
            ->addStep(
                $this->createLocalizedAttributesExtractorStep(
                    [
                        NavigationNodeWriterStep::KEY_TITLE,
                        NavigationNodeWriterStep::KEY_URL,
                        NavigationNodeWriterStep::KEY_CSS_CLASS,
                    ]
                )
            )
            ->addStep(
                $this->createNavigationNodeValidityDatesStep(
                    NavigationNodeWriterStep::KEY_VALID_FROM,
                    NavigationNodeWriterStep::KEY_VALID_TO
                )
            )
            ->addStep(new NavigationNodeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Navigation\NavigationKeyToIdNavigationStep
     */
    protected function createNavigationKeyToIdNavigationStep(
        $source = NavigationKeyToIdNavigationStep::KEY_SOURCE,
        $target = NavigationKeyToIdNavigationStep::KEY_TARGET
    ) {
        return new NavigationKeyToIdNavigationStep($source, $target);
    }

    /**
     * @param string $keyValidFrom
     * @param string $keyValidTo
     *
     * @return \Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeValidityDatesStep
     */
    protected function createNavigationNodeValidityDatesStep($keyValidFrom, $keyValidTo)
    {
        return new NavigationNodeValidityDatesStep($keyValidFrom, $keyValidTo);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductAbstractImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductAbstractHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductAbstractCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddCategoryKeysStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(ProductAbstractHydratorStep::COLUMN_TAX_SET_NAME))
            ->addStep($this->createAttributesExtractorStep())
            ->addStep(
                $this->createProductLocalizedAttributesExtractorStep(
                    [
                        ProductAbstractHydratorStep::COLUMN_NAME,
                        ProductAbstractHydratorStep::COLUMN_URL,
                        ProductAbstractHydratorStep::COLUMN_DESCRIPTION,
                        ProductAbstractHydratorStep::COLUMN_META_TITLE,
                        ProductAbstractHydratorStep::COLUMN_META_DESCRIPTION,
                        ProductAbstractHydratorStep::COLUMN_META_KEYWORDS,
                    ]
                )
            )
            ->addStep(new ProductAbstractHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductAbstractDataImportWriters());

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductAbstractStoreImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            ProductAbstractStoreHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker->addStep(new ProductAbstractStoreHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductAbstractStoreDataImportWriters());

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductConcreteImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductConcreteHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductConcreteCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAttributesExtractorStep())
            ->addStep($this->createProductConcreteAttributesUniqueCheckStep())
            ->addStep(
                $this->createProductLocalizedAttributesExtractorStep(
                    [
                        ProductConcreteHydratorStep::COLUMN_NAME,
                        ProductConcreteHydratorStep::COLUMN_DESCRIPTION,
                        ProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE,
                    ]
                )
            )
            ->addStep(
                new ProductConcreteHydratorStep(
                    $this->createProductRepository()
                )
            );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductConcreteDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductConcreteAttributesUniqueCheckStep(): DataImportStepInterface
    {
        return new ProductConcreteAttributesUniqueCheckStep(
            $this->createProductRepository(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractCheckExistenceStep()
    {
        return new ProductAbstractCheckExistenceStep(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductConcreteCheckExistenceStep()
    {
        return new ProductConcreteCheckExistenceStep(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductAbstractDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductAbstractWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductAbstractWriterPlugins()
    {
        return [
            new ProductAbstractPropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductConcreteDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductConcreteWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductConcreteWriterPlugins()
    {
        return [
            new ProductConcretePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductImageDataWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductImageWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductImageWriterPlugins()
    {
        return [
            new ProductImagePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductStockDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductStockWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductStockWriterPlugins()
    {
        return [
            new ProductStockPropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductAbstractStoreDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductAbstractStoreWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductAbstractStoreWriterPlugins()
    {
        return [
            new ProductAbstractStorePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractPropelWriter(): DataSetWriterInterface
    {
        return new ProductAbstractPropelDataSetWriter($this->createProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductConcretePropelWriter(): DataSetWriterInterface
    {
        return new ProductConcretePropelDataSetWriter($this->createProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductImagePropelWriter(): DataSetWriterInterface
    {
        return new ProductImagePropelDataSetWriter($this->createProductImageRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockPropelWriter(): DataSetWriterInterface
    {
        return new ProductStockPropelDataSetWriter(
            $this->getProductBundleFacade(),
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractStorePropelWriter(): DataSetWriterInterface
    {
        return new ProductAbstractStorePropelDataSetWriter();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected function createProductRepository()
    {
        return new ProductRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductAttributeKeyImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new ProductAttributeKeyWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductManagementAttributeImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep($this->createProductManagementLocalizedAttributesExtractorStep())
            ->addStep(new ProductManagementAttributeWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementLocalizedAttributesExtractorStep
     */
    protected function createProductManagementLocalizedAttributesExtractorStep()
    {
        return new ProductManagementLocalizedAttributesExtractorStep();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductGroupImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductGroupWriter::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(
                new ProductGroupWriter(
                    $this->createProductRepository()
                )
            );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createAbstractGiftCardConfigurationImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            GiftCardAbstractConfigurationWriterStep::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep(new GiftCardAbstractConfigurationWriterStep($this->createProductRepository()));
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createConcreteGiftCardConfigurationImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            GiftCardConcreteConfigurationWriterStep::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep(new GiftCardConcreteConfigurationWriterStep($this->createProductRepository()));
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductReviewImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(
            new ProductReviewWriterStep(
                $this->createProductRepository(),
                $this->createLocaleRepository()
            )
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductSetImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAbstractSkusStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createProductSetImageExtractorStep())
            ->addStep(
                $this->createLocalizedAttributesExtractorStep(
                    [
                        ProductSetWriterStep::KEY_NAME,
                        ProductSetWriterStep::KEY_URL,
                        ProductSetWriterStep::KEY_DESCRIPTION,
                        ProductSetWriterStep::KEY_META_TITLE,
                        ProductSetWriterStep::KEY_META_DESCRIPTION,
                        ProductSetWriterStep::KEY_META_KEYWORDS,
                    ]
                )
            )
            ->addStep(
                new ProductSetWriterStep(
                    $this->createProductRepository()
                )
            );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetImageExtractorStep
     */
    protected function createProductSetImageExtractorStep()
    {
        return new ProductSetImageExtractorStep();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductSearchAttributeMapImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep(new ProductSearchAttributeMapWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductSearchAttributeImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([ProductSearchAttributeWriter::KEY]))
            ->addStep(
                new ProductSearchAttributeWriter(
                    $this->createSearchGlossaryKeyBuilder()
                )
            );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->addAfterImportHook($this->createProductSearchAfterImportHook());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\Hook\ProductSearchAfterImportHook
     */
    protected function createProductSearchAfterImportHook()
    {
        return new ProductSearchAfterImportHook();
    }

    /**
     * @return \Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder
     */
    protected function createSearchGlossaryKeyBuilder()
    {
        return new FilterGlossaryKeyBuilder();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddCategoryKeysStep
     */
    protected function createAddCategoryKeysStep()
    {
        return new AddCategoryKeysStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep
     */
    protected function createAttributesExtractorStep()
    {
        return new AttributesExtractorStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep
     */
    protected function createCombinedAttributesExtractorStep()
    {
        return new CombinedAttributesExtractorStep();
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep
     */
    protected function createLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new LocalizedAttributesExtractorStep($defaultAttributes);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep
     */
    protected function createCombinedProductListProductConcreteAttributesExtractorStep()
    {
        return new CombinedProductListProductConcreteAttributesExtractorStep();
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep
     */
    protected function createProductLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new ProductLocalizedAttributesExtractorStep($defaultAttributes);
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep
     */
    protected function createCombinedProductLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new CombinedProductLocalizedAttributesExtractorStep($defaultAttributes);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddProductAbstractSkusStep
     */
    protected function createAddProductAbstractSkusStep()
    {
        return new AddProductAbstractSkusStep();
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Locale\LocaleNameToIdLocaleStep
     */
    protected function createLocaleNameToIdStep(
        $source = LocaleNameToIdLocaleStep::KEY_SOURCE,
        $target = LocaleNameToIdLocaleStep::KEY_TARGET
    ) {
        return new LocaleNameToIdLocaleStep($source, $target);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractSkuToIdProductAbstractStep
     */
    protected function createProductAbstractSkuToIdProductAbstractStep(
        string $source = ProductAbstractSkuToIdProductAbstractStep::KEY_SOURCE,
        string $target = ProductAbstractSkuToIdProductAbstractStep::KEY_TARGET
    ): ProductAbstractSkuToIdProductAbstractStep {
        return new ProductAbstractSkuToIdProductAbstractStep($source, $target);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductSkuToIdProductStep
     */
    protected function createProductSkuToIdProductStep(
        string $source = ProductSkuToIdProductStep::KEY_SOURCE,
        string $target = ProductSkuToIdProductStep::KEY_TARGET
    ): ProductSkuToIdProductStep {
        return new ProductSkuToIdProductStep($source, $target);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Tax\TaxSetNameToIdTaxSetStep
     */
    protected function createTaxSetNameToIdTaxSetStep(
        $source = TaxSetNameToIdTaxSetStep::KEY_SOURCE,
        $target = TaxSetNameToIdTaxSetStep::KEY_TARGET
    ) {
        return new TaxSetNameToIdTaxSetStep($source, $target);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\AddProductAttributeKeysStep
     */
    protected function createAddProductAttributeKeysStep()
    {
        return new AddProductAttributeKeysStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected function getEventFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface
     */
    public function getPriceProductOfferDataImportToPriceProductFacade(): PriceProductOfferDataImportToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(
            DataImportDependencyProvider::PRICE_PRODUCT_OFFER_DATA_IMPORT_TO_PRICE_PRODUCT_FACADE
        );
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected function getUtilTextService(): UtilTextServiceInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createAddLocalesStep(): DataImportStepInterface
    {
        return new AddLocalesStep($this->getStore());
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepositoryInterface
     */
    public function createProductImageRepository(): ProductImageRepositoryInterface
    {
        return new ProductImageRepository();
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareConditional
     */
    public function createDataImporterWriterAwareConditional($importType, DataReaderInterface $reader)
    {
        return new DataImporterDataSetWriterAwareConditional($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterConditional
     */
    public function getConditionalCsvDataImporterFromConfig(
        DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ) {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporterConditional($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareConditional
     */
    public function getConditionalCsvDataImporterWriterAwareFromConfig(
        DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
    ) {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporterWriterAwareConditional(
            $dataImporterConfigurationTransfer->getImportType(),
            $csvReader
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductPricePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductPricePropelDataSetWriter(
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductImagePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductImagePropelDataSetWriter(
            $this->createProductImageRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductStockPropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductStockPropelDataSetWriter(
            $this->getProductBundleFacade(),
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractStorePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractStorePropelDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractPropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractPropelDataSetWriter(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductConcretePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductConcretePropelDataSetWriter(
            $this->createProductRepository()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductPriceImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductPriceHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep(
                new CombinedProductPriceHydratorStep(
                    $this->getPriceProductFacade(),
                    $this->getUtilEncodingService()
                )
            );

        $dataImporter->setDataSetCondition($this->createCombinedProductPriceMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductPriceDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductPriceMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductPriceMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductPriceDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductPriceDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductPriceDataSetWriterPlugins(): array
    {
        return [
//            new CombinedProductPricePropelWriterPlugin(),
            new CombinedProductPriceBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductImageImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductImageHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep($this->createProductAbstractSkuToIdProductAbstractStep(CombinedProductImageHydratorStep::COLUMN_ABSTRACT_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT))
            ->addStep($this->createProductSkuToIdProductStep(CombinedProductImageHydratorStep::COLUMN_CONCRETE_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT))
//            ->addStep($this->createLocaleNameToIdStep(CombinedProductImageHydratorStep::COLUMN_LOCALE, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE))
            ->addStep(new CombinedProductImageHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductImageMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductImageDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductImageMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductImageMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductImageDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductImageDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductImageDataSetWriterPlugins(): array
    {
        return [
//            new CombinedProductImagePropelWriterPlugin(),
            new CombinedProductImageBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductStockImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductStockHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep(new CombinedProductStockHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductStockMandatoryColumnCondition());
        $dataImporter->setDataSetCondition($this->createCombinedProductStockTypeDataSetCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductStockDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductStockTypeDataSetCondition(): DataSetConditionInterface
    {
        return new CombinedProductStockTypeDataSetCondition();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductStockMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductStockMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductStockDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductStockDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductStockDataSetWriterPlugins(): array
    {
        return [
//            new CombinedProductStockPropelWriterPlugin(),
            new CombinedProductStockBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductAbstractStoreImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductAbstractStoreHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker->addStep(new CombinedProductAbstractStoreHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductAbstractStoreMandatoryColumnCondition());
        $dataImporter->setDataSetCondition($this->createCombinedProductAbstractStoreDataSetCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductAbstractStoreDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductAbstractStoreDataSetCondition(): DataSetConditionInterface
    {
        return new CombinedProductAbstractStoreDataSetCondition();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductAbstractStoreMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductAbstractStoreMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductAbstractStoreDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductAbstractStoreDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductAbstractStoreDataSetWriterPlugins(): array
    {
        return [
//            new CombinedProductAbstractStorePropelWriterPlugin(),
            new CombinedProductAbstractStoreBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductAbstractImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductAbstractHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker
//            ->addStep($this->createProductAbstractCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddCategoryKeysStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(CombinedProductAbstractHydratorStep::COLUMN_TAX_SET_NAME))
            ->addStep($this->createCombinedAttributesExtractorStep())
            ->addStep(
                $this->createCombinedProductLocalizedAttributesExtractorStep(
                    [
                        CombinedProductAbstractHydratorStep::COLUMN_NAME,
                        CombinedProductAbstractHydratorStep::COLUMN_URL,
                        CombinedProductAbstractHydratorStep::COLUMN_DESCRIPTION,
                        CombinedProductAbstractHydratorStep::COLUMN_META_TITLE,
                        CombinedProductAbstractHydratorStep::COLUMN_META_DESCRIPTION,
                        CombinedProductAbstractHydratorStep::COLUMN_META_KEYWORDS,
                    ]
                )
            )
            ->addStep(
                new CombinedProductAbstractHydratorStep(
                    $this->getUtilTextService()
                )
            );

        $dataImporter->setDataSetCondition($this->createCombinedProductAbstractTypeDataSetCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductAbstractDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductAbstractTypeDataSetCondition(): DataSetConditionInterface
    {
        return new CombinedProductAbstractTypeDataSetCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductAbstractDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductAbstractDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductAbstractDataSetWriterPlugins(): array
    {
        return [
//            new CombinedProductAbstractPropelWriterPlugin(),
            new CombinedProductAbstractBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductConcreteImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductConcreteHydratorStep::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createCombinedAttributesExtractorStep())
            ->addStep(
                $this->createCombinedProductLocalizedAttributesExtractorStep(
                    [
                        CombinedProductConcreteHydratorStep::COLUMN_NAME,
                        CombinedProductConcreteHydratorStep::COLUMN_DESCRIPTION,
                        CombinedProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE,
                    ]
                )
            )
            ->addStep(
                new CombinedProductConcreteHydratorStep(
                    $this->createProductRepository()
                )
            );

        $dataImporter->setDataSetCondition($this->createCombinedProductConcreteTypeDataSetCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductConcreteDataSetWriters());

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedProductGroupImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductGroupBulkPdoMariaDbDataSetWriter::BULK_SIZE);

        $dataImporter->setDataSetCondition($this->createCombinedProductGroupMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductGroupDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductGroupDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductGroupDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductGroupDataSetWriterPlugins(): array
    {
        return [
            new CombinedProductGroupBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductConcreteTypeDataSetCondition(): DataSetConditionInterface
    {
        return new CombinedProductConcreteTypeDataSetCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductConcreteDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductConcreteDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductConcreteDataSetWriterPlugins(): array
    {
        return [
//            new CombinedProductConcretePropelWriterPlugin(),
            new CombinedProductConcreteBulkPdoMariaDbWriterPlugin(),
        ];
    }

//    /**
//     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//     *
//     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
//     */
//    public function createCombinedProductListProductConcreteImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
//    {
//        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
//            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
//        );
//
//        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
//            CombinedProductListProductConcreteWriter::BULK_SIZE
//        );
//        $dataSetStepBroker
//            ->addStep($this->createCombinedProductListProductConcreteAttributesExtractorStep())
//            ->addStep($this->createProductSkuToIdProductStep(
//                CombinedProductListProductConcreteWriter::COLUMN_CONCRETE_SKU,
//                CombinedProductListProductConcreteWriter::KEY_ID_PRODUCT_CONCRETE
//            ))
//            ->addStep(new CombinedProductListProductConcreteWriter());
//
//        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
//        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
//
//        return $dataImporter;
//    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCombinedProductListProductConcreteImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ) {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                $dataImportConfigurationActionTransfer
            )
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            CombinedProductListProductConcreteWriter::BULK_SIZE
        );
        $dataSetStepBroker
            ->addStep($this->createCombinedProductListProductConcreteAttributesExtractorStep());
//            ->addStep($this->createProductSkuToIdProductStep(
//                CombinedProductListProductConcreteWriter::COLUMN_CONCRETE_SKU,
//                CombinedProductListProductConcreteWriter::KEY_ID_PRODUCT_CONCRETE
//            ))
//            ->addStep(new CombinedProductListProductConcreteWriter());

        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductListProductConcreteDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductListProductConcreteDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createCombinedProductListProductConcreteDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createCombinedProductListProductConcreteDataSetWriterPlugins(): array
    {
        return [
            new CombinedProductListProductConcreteBulkPdoMariaDbWriterPlugin(),
        ];
    }

//    /**
//     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//     *
//     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
//     */
//    public function createMerchantProductOfferImporter(
//        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//    ): ?DataImporterInterface {
//        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
//            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
//                $dataImportConfigurationActionTransfer
//            )
//        );
//
//        $dataImporter->addBeforeImportHook(
//            $this->createDisableMerchantProductOffersBeforeImport($dataImportConfigurationActionTransfer)
//        );
//        $dataImporter->addAfterImportHook($this->createDisableProductsWithoutOfferAfterImport());
//        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(MerchantProductOfferWriterStep::BULK_SIZE);
//        $dataSetStepBroker->addStep($this->createMerchantReferenceToIdMerchantStep());
//        $dataSetStepBroker->addStep($this->createConcreteSkuValidationStep());
//        $dataSetStepBroker->addStep($this->createMerchantProductOfferWriterStep());
//
//        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
//        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
//
//        return $dataImporter;
//    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getMerchantProductOfferImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?DataImporterInterface {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataImporter->addBeforeImportHook(
            $this->createDisableMerchantProductOffersBeforeImport($dataImportConfigurationActionTransfer)
        );
        $dataImporter->addAfterImportHook(
            $this->createDisableProductsWithoutOfferAfterImport($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(MerchantProductOfferStoreBulkPdoMariaDbDataSetWriter::BULK_SIZE);
        $dataSetStepBroker->addStep($this->createMerchantReferenceToIdMerchantStep());
        $dataSetStepBroker->addStep($this->createAffiliateDataStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createMerchantProductOfferDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createMerchantProductOfferDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createMerchantProductOfferDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createMerchantProductOfferDataSetWriterPlugins(): array
    {
        return [
            new MerchantProductOfferBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createMerchantProductOfferBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new MerchantProductOfferBulkPdoMariaDbDataSetWriter(
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getEventFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\DisableMerchantProductOffersBeforeImport
     */
    public function createDisableMerchantProductOffersBeforeImport(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): DisableMerchantProductOffersBeforeImport {
        return new DisableMerchantProductOffersBeforeImport(
            $this->createCsvReaderFromConfig(
                $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                    $dataImportConfigurationActionTransfer
                )->getReaderConfiguration()
            )->current(),
            $this->getEventFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\DisableProductsWithoutOfferAfterImport
     */
    public function createDisableProductsWithoutOfferAfterImport(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer): DisableProductsWithoutOfferAfterImport
    {
        return new DisableProductsWithoutOfferAfterImport(
            $this->createCsvReaderFromConfig(
                $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
                    $dataImportConfigurationActionTransfer
                )->getReaderConfiguration()
            )->current(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\MerchantReferenceToIdMerchantStep
     */
    public function createMerchantReferenceToIdMerchantStep(): MerchantReferenceToIdMerchantStep
    {
        return new MerchantReferenceToIdMerchantStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\ConcreteSkuValidationStep
     */
    public function createConcreteSkuValidationStep(): ConcreteSkuValidationStep
    {
        return new ConcreteSkuValidationStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer\MerchantProductOfferWriterStep
     */
    public function createMerchantProductOfferWriterStep(): MerchantProductOfferWriterStep
    {
        return new MerchantProductOfferWriterStep($this->getEventFacade());
    }

//    /**
//     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//     *
//     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
//     */
//    public function createMerchantProductOfferStoreImporter(
//        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//    ): ?DataImporterInterface {
//        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
//            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
//                $dataImportConfigurationActionTransfer
//            )
//        );
//        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
//            MerchantProductOfferStoreWriterStep::BULK_SIZE
//        );
//        $dataSetStepBroker->addStep($this->createProductOfferReferenceToIdProductOfferStep());
//        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
//        $dataSetStepBroker->addStep($this->createMerchantProductOfferStoreWriterStep());
//
//        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
//        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
//        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
//
//        return $dataImporter;
//    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getMerchantProductOfferStoreImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?DataImporterInterface {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(
            MerchantProductOfferStoreBulkPdoMariaDbDataSetWriter::BULK_SIZE
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
        $dataImporter->setDataSetWriter($this->createMerchantProductOfferStoreDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createMerchantProductOfferStoreDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createMerchantProductOfferStoreDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createMerchantProductOfferStoreDataSetWriterPlugins(): array
    {
        return [
            new MerchantProductOfferStoreBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createMerchantProductOfferStoreBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new MerchantProductOfferStoreBulkPdoMariaDbDataSetWriter(
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\ProductOfferReferenceToIdProductOfferStep
     */
    public function createProductOfferReferenceToIdProductOfferStep(): ProductOfferReferenceToIdProductOfferStep
    {
        return new ProductOfferReferenceToIdProductOfferStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\StoreNameToIdStoreStep
     */
    public function createStoreNameToIdStoreStep(): StoreNameToIdStoreStep
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer\MerchantProductOfferStoreWriterStep
     */
    public function createMerchantProductOfferStoreWriterStep(): MerchantProductOfferStoreWriterStep
    {
        return new MerchantProductOfferStoreWriterStep($this->getEventFacade());
    }

//    /**
//     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//     *
//     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
//     */
//    public function createPriceProductOfferImporter(
//        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
//    ): ?DataImporterInterface {
//        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
//            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction(
//                $dataImportConfigurationActionTransfer
//            )
//        );
//        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(PriceProductStoreWriterStep::BULK_SIZE);
//        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());
//
//        $dataSetStepBroker->addStep($this->createProductOfferReferenceToProductOfferDataStep());
//        $dataSetStepBroker->addStep($this->createProductOfferToIdProductStep());
//        $dataSetStepBroker->addStep($this->createPriceTypeToIdPriceTypeStep());
//        $dataSetStepBroker->addStep($this->createPriceProductWriterStep());
//        $dataSetStepBroker->addStep($this->createStoreToIdStoreStep());
//        $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
//        $dataSetStepBroker->addStep($this->createPreparePriceDataStep());
//        $dataSetStepBroker->addStep($this->createPriceProductStoreWriterStep());
//        $dataSetStepBroker->addStep($this->createPriceProductOfferWriterStep());
//
//        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
//
//        return $dataImporter;
//    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getPriceProductOfferImporter(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): ?DataImporterInterface {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(PriceProductStoreWriterStep::BULK_SIZE);
        $dataImporter->setDataSetCondition($this->createCombinedProductListProductConcreteMandatoryColumnCondition());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createPriceProductOfferDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\AffiliateDataStep
     */
    public function createAffiliateDataStep(): AffiliateDataStep
    {
        return new AffiliateDataStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createPriceProductOfferBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new PriceProductOfferBulkPdoMariaDbDataSetWriter(
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getPriceProductOfferDataImportToPriceProductFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createPriceProductOfferDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createPriceProductOfferDataSetWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createPriceProductOfferDataSetWriterPlugins(): array
    {
        return [
            new PriceProductOfferBulkPdoMariaDbWriterPlugin(),
        ];
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\ProductOfferReferenceToProductOfferDataStep
     */
    public function createProductOfferReferenceToProductOfferDataStep(): ProductOfferReferenceToProductOfferDataStep
    {
        return new ProductOfferReferenceToProductOfferDataStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\ProductOfferToIdProductStep
     */
    public function createProductOfferToIdProductStep(): ProductOfferToIdProductStep
    {
        return new ProductOfferToIdProductStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\PriceTypeToIdPriceTypeStep
     */
    public function createPriceTypeToIdPriceTypeStep(): PriceTypeToIdPriceTypeStep
    {
        return new PriceTypeToIdPriceTypeStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\PriceProductWriterStep
     */
    public function createPriceProductWriterStep(): PriceProductWriterStep
    {
        return new PriceProductWriterStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\StoreToIdStoreStep
     */
    public function createStoreToIdStoreStep(): StoreToIdStoreStep
    {
        return new StoreToIdStoreStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\CurrencyToIdCurrencyStep
     */
    public function createCurrencyToIdCurrencyStep(): CurrencyToIdCurrencyStep
    {
        return new CurrencyToIdCurrencyStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\PreparePriceDataStep
     */
    public function createPreparePriceDataStep(): PreparePriceDataStep
    {
        return new PreparePriceDataStep(
            $this->getPriceProductOfferDataImportToPriceProductFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer\PriceProductStoreWriterStep
     */
    public function createPriceProductStoreWriterStep(): PriceProductStoreWriterStep
    {
        return new PriceProductStoreWriterStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer\PriceProductOfferWriterStep
     */
    public function createPriceProductOfferWriterStep(): PriceProductOfferWriterStep
    {
        return new PriceProductOfferWriterStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductGroupMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductGroupMandatoryColumnCondition();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductListProductConcreteMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductListProductConcreteMandatoryColumnCondition();
    }

// => CTE

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractBulkPdoMariaDbDataSetWriter(
            $this->createProductAbstractMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface
     */
    protected function createProductAbstractMariaDbSql(): ProductAbstractSqlInterface
    {
        return new ProductAbstractMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractStoreBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter(
            $this->createProductAbstractStoreMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface
     */
    protected function createProductAbstractStoreMariaDbSql(): ProductAbstractStoreSqlInterface
    {
        return new ProductAbstractStoreMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductConcreteBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductConcreteBulkPdoMariaDbDataSetWriter(
            $this->createProductConcreteMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface
     */
    protected function createProductConcreteMariaDbSql(): ProductConcreteSqlInterface
    {
        return new ProductConcreteMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductImageBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductImageBulkPdoMariaDbDataSetWriter(
            $this->createProductImageMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface
     */
    protected function createProductImageMariaDbSql(): ProductImageSqlInterface
    {
        return new ProductImageMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductPriceBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductPriceBulkPdoMariaDbDataSetWriter(
            $this->createProductPriceMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface
     */
    protected function createProductPriceMariaDbSql(): ProductPriceSqlInterface
    {
        return new ProductPriceMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductStockBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductStockBulkPdoMariaDbDataSetWriter(
            $this->getStockFacade(),
            $this->getProductBundleFacade(),
            $this->createProductStockMariaDbSql(),
            $this->createPropelExecutor(),
            $this->getStoreFacade(),
            $this->createDataFormatter(),
            $this->getConfig(),
            $this->createProductStockReader()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface
     */
    protected function createProductStockMariaDbSql(): ProductStockSqlInterface
    {
        return new ProductStockMariaDbSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface
     */
    public function createProductStockReader(): ProductStockReaderInterface
    {
        return new ProductStockReader();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductGroupBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new ProductGroupBulkPdoMariaDbDataSetWriter(
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->createProductGroupMariaDbSql()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql\ProductGroupSqlInterface
     */
    protected function createProductGroupMariaDbSql(): ProductGroupSqlInterface
    {
        return new ProductGroupMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductListProductConcreteBulkMariaDbPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductListProductConcreteBulkPdoMariaDbDataSetWriter(
            $this->createProductListProductConcreteMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql\ProductListProductConcreteSqlInterface
     */
    protected function createProductListProductConcreteMariaDbSql(): ProductListProductConcreteSqlInterface
    {
        return new ProductListProductConcreteMariaDbSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    public function createPropelExecutor(): PropelExecutorInterface
    {
        return new PropelExecutor();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected function createDataFormatter(): DataImportDataFormatterInterface
    {
        return new DataImportDataFormatter(
            $this->getUtilEncodingService()
        );
    }
}
