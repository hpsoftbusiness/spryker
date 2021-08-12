<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice;

use Generated\Shared\Transfer\SpyCurrencyEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;
use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Generated\Shared\Transfer\SpyStoreEntityTransfer;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;

class CombinedProductPriceHydratorStep extends ProductPriceHydratorStep
{
    public const BULK_SIZE = 100;

    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';

    public const COLUMN_CURRENCY = 'product_price.currency';
    public const COLUMN_STORE = 'product_price.store';
    public const COLUMN_PRICE_NET = 'product.value_56';
    public const COLUMN_PRICE_GROSS_ORIGINAL = 'product.value_57';
    public const COLUMN_PRICE_GROSS = 'product.value_58';
    public const COLUMN_BENEFIT_PRICE = 'product.value_59';
    public const COLUMN_PRICE_DATA = 'product_price.price_data';
    public const COLUMN_PRICE_DATA_CHECKSUM = 'product_price.price_data_checksum';
    public const COLUMN_PRICE_TYPE = 'product_price.price_type';
    public const COLUMN_ASSIGNED_PRODUCT_TYPE = 'product_price.assigned_product_type';

    public const KEY_PRICE_DATA_PREFIX = 'product_price.price_data.';

    protected const ASSIGNABLE_PRODUCT_TYPE_ABSTRACT = 'abstract';
    protected const ASSIGNABLE_PRODUCT_TYPE_CONCRETE = 'concrete';
    protected const ASSIGNABLE_PRODUCT_TYPES = [
        self::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
        self::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
    ];

    public const COLUMN_IS_AFFILIATE_PRODUCT = 'product.value_73';
    public const COLUMN_AFFILIATE_PRODUCT_PRICE = 'product.value_75';

    public const DEFAULT_PRICE_TYPE = 'DEFAULT';
    public const ORIGINAL_PRICE_TYPE = 'ORIGINAL';

    public const COLUMN_PRICE_PRODUCT_STORE_KEY = 'price_product_store_key';

    /**
     * @link \Pyz\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_BENEFIT
     */
    public const BENEFIT_PRICE_TYPE = 'BENEFIT';

    /**
     * @var array
     */
    public static $priceTypes = [
        self::DEFAULT_PRICE_TYPE => self::COLUMN_PRICE_GROSS,
        self::ORIGINAL_PRICE_TYPE => self::COLUMN_PRICE_GROSS_ORIGINAL,
        self::BENEFIT_PRICE_TYPE => self::COLUMN_BENEFIT_PRICE,
    ];

    /**
     * @var array
     */
    public static $priceTypeEntityTransfers = [];

    /**
     * @var array
     */
    public static $productPriceTransfers = [];

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductFacadeInterface $priceProductFacade,
        DataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        parent::__construct($priceProductFacade, $utilEncodingService);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->importPriceData($dataSet);

        foreach (static::$priceTypes as $priceType => $priceAttributeKey) {
            if (isset($dataSet[$priceAttributeKey])) {
                $dataSet[static::COLUMN_PRICE_TYPE] = $priceType;

                $this->importPriceType($dataSet);
                $this->importProductPrice($dataSet);
            }
        }

        $dataSet[static::PRICE_TYPE_TRANSFER] = static::$priceTypeEntityTransfers;
        $dataSet[static::PRICE_PRODUCT_TRANSFER] = static::$productPriceTransfers;

        static::$priceTypeEntityTransfers = [];
        static::$productPriceTransfers = [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceTypeEntityTransfer
     */
    protected function importPriceType(DataSetInterface $dataSet): SpyPriceTypeEntityTransfer
    {
        $priceTypeTransfer = $this->getPriceType($dataSet);

        static::$priceTypeEntityTransfers[] = $priceTypeTransfer;

        return $priceTypeTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceTypeEntityTransfer
     */
    protected function getPriceType(DataSetInterface $dataSet): SpyPriceTypeEntityTransfer
    {
        $priceTypeTransfer = new SpyPriceTypeEntityTransfer();
        $priceTypeTransfer
            ->setName($dataSet[static::COLUMN_PRICE_TYPE] ?: static::DEFAULT_PRICE_TYPE)
            ->setPriceModeConfiguration(static::KEY_DEFAULT_PRICE_MODE_CONFIGURATION);

        return $priceTypeTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    protected function importProductPrice(DataSetInterface $dataSet): void
    {
        $productPriceTransfer = new SpyPriceProductEntityTransfer();

        if (empty($dataSet[static::COLUMN_ABSTRACT_SKU]) && empty($dataSet[static::COLUMN_CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                'One of "%s" or "%s" must be in the data set. Given: "%s"',
                $dataSet[static::COLUMN_ABSTRACT_SKU],
                $dataSet[static::COLUMN_CONCRETE_SKU],
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!empty($dataSet[static::COLUMN_ABSTRACT_SKU])) {
            $productPriceTransfer->setSpyProductAbstract($this->importProductAbstract($dataSet));
        }

        if (!empty($dataSet[static::COLUMN_CONCRETE_SKU])) {
            $productPriceTransfer->setProduct($this->importProductConcrete($dataSet));
        }

        $productPriceTransfer
            ->setPriceType($this->importPriceType($dataSet))
            ->addSpyPriceProductStores($this->importPriceProductStore($dataSet));

        static::$productPriceTransfers[] = $productPriceTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer
     */
    protected function importPriceProductStore(DataSetInterface $dataSet): SpyPriceProductStoreEntityTransfer
    {
        $currencyEntityTransfer = new SpyCurrencyEntityTransfer();
//        TODO:: changed EUR to default currency
        $currencyEntityTransfer->setName($dataSet[static::COLUMN_CURRENCY] ?: 'EUR');
//        TODO:: changed DE to default store
        $storeEntityTransfer = new SpyStoreEntityTransfer();
        $storeEntityTransfer->setName($dataSet[static::COLUMN_STORE] ?: 'DE');

        if ($dataSet[static::COLUMN_PRICE_TYPE] === self::BENEFIT_PRICE_TYPE) {
            $netPrice = $this->getPriceValue($dataSet[self::COLUMN_BENEFIT_PRICE]);
            $grossPrice = $this->getPriceValue($dataSet[self::COLUMN_BENEFIT_PRICE]);
        } else {
            $netPrice = $this->getNetPrice($dataSet);
            $grossPrice = $this->getGrossPrice($dataSet);
        }

        if ($dataSet[CombinedProductPriceHydratorStep::COLUMN_IS_AFFILIATE_PRODUCT] === 'TRUE') {
            $price = (int)((string)((float)str_replace(',', '.', $dataSet[CombinedProductPriceHydratorStep::COLUMN_AFFILIATE_PRODUCT_PRICE]) * 100));
            $grossPrice = $netPrice = $dataSet[CombinedProductPriceHydratorStep::COLUMN_PRICE_TYPE] !== CombinedProductPriceHydratorStep::ORIGINAL_PRICE_TYPE ? $price : null;
        }

        $priceProductStoreEntityTransfer = new SpyPriceProductStoreEntityTransfer();
        $priceProductStoreEntityTransfer
            ->setCurrency($currencyEntityTransfer)
            ->setStore($storeEntityTransfer)
            ->setNetPrice($netPrice)
            ->setGrossPrice($grossPrice)
            ->setPriceData($dataSet[static::COLUMN_PRICE_DATA])
            ->setPriceDataChecksum($dataSet[static::COLUMN_PRICE_DATA_CHECKSUM]);

        return $priceProductStoreEntityTransfer;
    }

    /**
     * requirement by MYW-819
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int|null
     */
    protected function getNetPrice(DataSetInterface $dataSet): ?int
    {
        $defaultNetPriceKey = static::COLUMN_PRICE_NET;

        if ($dataSet[CombinedProductPriceHydratorStep::COLUMN_PRICE_TYPE] ===
            CombinedProductPriceHydratorStep::DEFAULT_PRICE_TYPE && (int)$dataSet[$defaultNetPriceKey] > 0) {
            return $this->getPriceValue($dataSet[$defaultNetPriceKey]);
        }

        return null;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return float|null
     */
    protected function getGrossPrice(DataSetInterface $dataSet): ?float
    {
        $defaultGrossPriceKey = static::COLUMN_PRICE_GROSS;

        if ($dataSet[CombinedProductPriceHydratorStep::COLUMN_PRICE_TYPE] === CombinedProductPriceHydratorStep::ORIGINAL_PRICE_TYPE) {
            if ($dataSet[$defaultGrossPriceKey] === $dataSet[static::COLUMN_PRICE_GROSS_ORIGINAL]) {
                return null;
            }
            $defaultGrossPriceKey = static::COLUMN_PRICE_GROSS_ORIGINAL;
        }

        if (!empty($dataSet[$defaultGrossPriceKey])) {
            return $this->getPriceValue($dataSet[$defaultGrossPriceKey]);
        }

        return null;
    }

    /**
     * @param string $price
     *
     * @return int
     */
    private function getPriceValue(string $price): int
    {
        return (int)((string)((float)str_replace(',', '.', $price) * 100));
    }
}
