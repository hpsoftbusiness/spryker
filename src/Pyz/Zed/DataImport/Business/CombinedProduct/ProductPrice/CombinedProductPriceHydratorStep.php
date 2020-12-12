<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice;

use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Pyz\Zed\DataImport\Business\Exception\InvalidDataException;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;

class CombinedProductPriceHydratorStep extends ProductPriceHydratorStep
{
    public const BULK_SIZE = 5000;

    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';

    public const COLUMN_CURRENCY = 'product_price.currency';
    public const COLUMN_STORE = 'product_price.store';
    public const COLUMN_PRICE_NET = 'product.value_56';
    public const COLUMN_PRICE_GROSS_ORIGINAL = 'product.value_57';
    public const COLUMN_PRICE_GROSS = 'product.value_58';
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

    public static $priceTypes = [
        self::DEFAULT_PRICE_TYPE => self::COLUMN_PRICE_GROSS,
        self::ORIGINAL_PRICE_TYPE => self::COLUMN_PRICE_GROSS_ORIGINAL,
    ];

    public static $priceTypeEntityTransfers = [];

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
        $dataSet = $this->assignProductType($dataSet);

        $this->importPriceData($dataSet);

        foreach (static::$priceTypes as $priceType => $priceAttributeKey) {
            $dataSet[static::COLUMN_PRICE_TYPE] = $priceType;
            $this->importPriceType($dataSet);
            $this->importProductPrice($dataSet);
        }

        $dataSet[static::PRICE_TYPE_TRANSFER] = static::$priceTypeEntityTransfers;
        static::$priceTypeEntityTransfers = [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function assignProductType(DataSetInterface $dataSet): DataSetInterface
    {
        $isAbstractSkuIsEmpty = $dataSet[static::COLUMN_ABSTRACT_SKU] ?: null;
        $isConcreteSkuIsEmpty = $dataSet[static::COLUMN_CONCRETE_SKU] ?: null;

        if ($isAbstractSkuIsEmpty === null) {
            $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] = static::ASSIGNABLE_PRODUCT_TYPE_CONCRETE;
        }

        if ($isConcreteSkuIsEmpty === null) {
            $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] = static::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT;
        }

        if ($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] === "") {
            $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] = static::ASSIGNABLE_PRODUCT_TYPE_CONCRETE;
        }

        if ($dataSet[static::COLUMN_PRICE_TYPE] === "") {
            $dataSet[static::COLUMN_PRICE_TYPE] = static::DEFAULT_PRICE_TYPE;
        }

        $this->assertAssignableProductTypeColumn($dataSet);

        if ($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] == static::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT) {
            $dataSet[static::COLUMN_CONCRETE_SKU] = null;
        }
        if ($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE] == static::ASSIGNABLE_PRODUCT_TYPE_CONCRETE) {
            $dataSet[static::COLUMN_ABSTRACT_SKU] = null;
        }

        return $dataSet;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Pyz\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function assertAssignableProductTypeColumn(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" must be defined in the data set. Given: "%s"',
                static::COLUMN_ASSIGNED_PRODUCT_TYPE,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!in_array($dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE], static::ASSIGNABLE_PRODUCT_TYPES, true)) {
            throw new InvalidDataException(sprintf(
                '"%s" must have one of the following values: %s. Given: "%s"',
                static::COLUMN_ASSIGNED_PRODUCT_TYPE,
                implode(', ', static::ASSIGNABLE_PRODUCT_TYPES),
                $dataSet[static::COLUMN_ASSIGNED_PRODUCT_TYPE]
            ));
        }
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
            ->setName($dataSet[static::COLUMN_PRICE_TYPE])
            ->setPriceModeConfiguration(static::KEY_DEFAULT_PRICE_MODE_CONFIGURATION);

        return $priceTypeTransfer;
    }
}
