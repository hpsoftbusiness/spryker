<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\PriceProductStore\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents;
use Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface;

class PriceProductOfferBulkPdoMariaDbDataSetWriter implements DataSetWriterInterface
{
    public const BULK_SIZE = 100;

    protected const COLUMN_PRICE_TYPE = 'price_type';
    protected const COLUMN_STORE_NAME = 'store_name';
    protected const COLUMN_CURRENCY = 'currency';
    protected const COLUMN_NET_PRICE = 'net_price';
    protected const COLUMN_GROSS_PRICE = 'gross_price';
    protected const COLUMN_VOLUME_PRICES = 'volume_prices';
    protected const COLUMN_CONCRETE_SKU = 'concrete_sku';
    protected const COLUMN_MERCHANT_REFERENCE = 'merchant_reference';
    protected const COLUMN_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    protected const KEY_PRICE_TYPE = 'product_price.price_type';
    protected const KEY_STORE_NAME = 'product_abstract_store.store_name';
    protected const KEY_CURRENCY = 'product_price.currency';
    protected const KEY_VALUE_NET = 'product.value_75';
    protected const KEY_VALUE_GROSS = 'product.value_75';
    protected const KEY_VOLUME_PRICES = 'product_price.price_data.volume_prices';
    protected const KEY_CONCRETE_SKU = 'concrete_sku';
    protected const KEY_MERCHANT_REFERENCE = 'product.value_10';
    protected const KEY_ID_PRODUCT_OFFER = 'id_product_offer';
    protected const KEY_ID_PRICE_TYPE = 'id_price_type';
    protected const KEY_ID_PRODUCT = 'id_product';
    protected const KEY_ID_PRICE_PRODUCT = 'id_price_product';
    protected const KEY_ID_STORE = 'id_store';
    protected const KEY_ID_CURRENCY = 'id_currency';
    protected const KEY_ID_PRICE_PRODUCT_STORE = 'id_price_product_store';
    protected const KEY_ID_PRICE_PRODUCT_OFFER = 'id_price_product_offer';
    protected const KEY_PRICE_PRODUCT_STORE_KEY = 'price_product_store_key';

    protected const COLUMN_PRICE_DATA = 'price_data';
    protected const COLUMN_PRICE_DATA_CHECKSUM = 'price_data_checksum';

    protected const DEFAULT_PRICE_TYPE = 'DEFAULT';
    protected const ORIGINAL_PRICE_TYPE = 'ORIGINAL';

    protected const IS_AFFILIATE_KEY = 'product.value_73';

    /**
     * @var array
     */
    protected static $priceProductOfferStoreCollection = [];

    /**
     * @var array
     */
    protected static $productOfferIds = [];

    /**
     * @var array
     */
    protected static $priceTypeIds = [];

    /**
     * @var array
     */
    protected static $productIds = [];

    /**
     * @var array
     */
    protected static $priceProductIds = [];

    /**
     * @var array
     */
    protected static $storeIds = [];

    /**
     * @var array
     */
    protected static $currencyIds = [];

    /**
     * @var array
     */
    protected static $priceProductStoreIds = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade\PriceProductOfferDataImportToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        PriceProductOfferDataImportToPriceProductFacadeInterface $priceProductFacade,
        DataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
        $this->priceProductFacade = $priceProductFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        $this->collectPriceProductOfferCollection($dataSet);

        if (count(static::$priceProductOfferStoreCollection) >= static::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        if (static::$priceProductOfferStoreCollection === []) {
            return;
        }

        $this->collectProductOffersIds();
        $this->collectPriceTypeIds();
        $this->collectProductIds();
        $this->persistPriceProduct();
        $this->collectStoreIds();
        $this->collectCurrencyIds();
        $this->persistPriceProductStore();
        $this->persistPriceProductOffer();

        static::$productOfferIds = [];
        static::$priceTypeIds = [];
        static::$productIds = [];
        static::$priceProductIds = [];
        static::$storeIds = [];
        static::$currencyIds = [];
        static::$priceProductStoreIds = [];
        static::$priceProductOfferStoreCollection = [];
    }

    /**
     * @return void
     */
    protected function collectProductOffersIds(): void
    {
        $productOfferReferenceCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, self::COLUMN_PRODUCT_OFFER_REFERENCE);

        $rowCount = count($productOfferReferenceCollection);
        $productOfferReference = $this->dataFormatter->formatStringList($productOfferReferenceCollection, $rowCount);

        $sql = '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.productOfferReference,
                  spy_product_offer.id_product_offer as id_product_offer
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productOfferReferences, \',\', n.digit + 1), \',\', -1), \'\') as productOfferReference
                       FROM (
                            SELECT ? as productOfferReferences
                       ) temp
                       INNER JOIN n
                            ON LENGTH(REPLACE(productOfferReferences, \',\', \'\')) <= LENGTH(productOfferReferences) - n.digit
                    ) input
                    LEFT JOIN spy_product_offer ON spy_product_offer.product_offer_reference = input.productOfferReference
            ) SELECT records.id_product_offer FROM records';

        $result = $this->propelExecutor->execute($sql, [
            $rowCount,
            $productOfferReference,
        ]);

        foreach ($result as $productOfferData) {
            static::$productOfferIds[][static::KEY_ID_PRODUCT_OFFER] = (int)$productOfferData[static::KEY_ID_PRODUCT_OFFER];
        }
    }

    /**
     * @return void
     */
    protected function collectPriceTypeIds(): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, self::COLUMN_PRICE_TYPE);

        $uniqueTypes = array_unique($priceTypeCollection);
        $uniqueDataTypes = $this->dataFormatter->formatStringList($uniqueTypes);
        $priceModeConfiguration = $this->dataFormatter->formatStringList(array_fill(0, count($uniqueTypes), 2));

        $sql = 'INSERT INTO spy_price_type (
            id_price_type,
            name,
            price_mode_configuration
         )
            WITH RECURSIVE n(digit) AS (
            SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
            SELECT
                  input.name,
                  input.price_mode_configuration,
                  spy_price_type.id_price_type as price_type_id
                FROM (
                    SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.names, \',\', n.digit + 1), \',\', -1), \'\') as name,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_mode_configurations, \',\', n.digit + 1), \',\', -1), \'\') as price_mode_configuration
                    FROM (
                        SELECT ? as names,
                               ? as price_mode_configurations
                    ) temp
                    INNER JOIN n
                      ON LENGTH(REPLACE(names, \',\', \'\')) <= LENGTH(names) - n.digit
                          AND LENGTH(REPLACE(price_mode_configurations, \',\', \'\')) <= LENGTH(price_mode_configurations) - n.digit
                ) input
                LEFT JOIN spy_price_type ON spy_price_type.name = input.name
            )
            (
                SELECT
                    price_type_id,
                    name,
                    price_mode_configuration
                FROM records
            )
            ON DUPLICATE KEY UPDATE name = records.name,
                price_mode_configuration = records.price_mode_configuration';

        $this->propelExecutor->execute($sql, [
            count($uniqueTypes),
            $uniqueDataTypes,
            $priceModeConfiguration,
        ], false);

        $rowCount = count($priceTypeCollection);
        $priceType = $this->dataFormatter->formatStringList($priceTypeCollection, $rowCount);

        $sql = '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.price_type,
                  spy_price_type.id_price_type
                FROM (
                   SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_types, \',\', n.digit + 1), \',\', -1), \'\') as price_type
                   FROM (
                        SELECT ? as price_types
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(price_types, \',\', \'\')) <= LENGTH(price_types) - n.digit
                ) input
                LEFT JOIN spy_price_type ON spy_price_type.name = input.price_type
            ) SELECT records.id_price_type FROM records';

        $result = $this->propelExecutor->execute($sql, [
            $rowCount,
            $priceType,
        ]);

        foreach ($result as $priceTypeData) {
            static::$priceTypeIds[][static::KEY_ID_PRICE_TYPE] = (int)$priceTypeData[static::KEY_ID_PRICE_TYPE];
        }
    }

    /**
     * @return void
     */
    protected function collectProductIds(): void
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, self::COLUMN_CONCRETE_SKU);

        $rowCount = count($productCollection);
        $orderKey = $this->dataFormatter->formatStringList(array_keys($productCollection), $rowCount);
        $product = $this->dataFormatter->formatStringList($productCollection, $rowCount);

        $sql = '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.sku,
                  spy_product.id_product
                FROM (
                   SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku
                   FROM (
                        SELECT ? as skus
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                ) input
                LEFT JOIN spy_product ON spy_product.sku = input.sku
            ) SELECT records.id_product FROM records';

        $result = $this->propelExecutor->execute($sql, [
            $rowCount,
            $product,
        ]);

        foreach ($result as $productData) {
            static::$productIds[][static::KEY_ID_PRODUCT] = (int)$productData[static::KEY_ID_PRODUCT];
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProduct(): void
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, static::KEY_ID_PRODUCT);

        $rowCount = count($productCollection);
        $product = $this->dataFormatter->formatStringList($productCollection, $rowCount);

        $priceType = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceTypeIds, static::KEY_ID_PRICE_TYPE),
            $rowCount
        );

        $sql = "
            INSERT INTO spy_price_product (
                      id_price_product,
                      fk_price_type,
                      fk_product
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.id_product,
                  input.id_price_type,
                  spy_price_product.id_price_product as idPriceProduct
                FROM (
                        SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_products, ',', n.digit + 1), ',', -1), '') as id_product,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_price_types, ',', n.digit + 1), ',', -1), '') as id_price_type
                        FROM (
                            SELECT ? as id_products,
                                   ? as id_price_types
                        ) temp
                        INNER JOIN n
                          ON LENGTH(REPLACE(id_products, ',', '')) <= LENGTH(id_products) - n.digit
                              AND LENGTH(REPLACE(id_price_types, ',', '')) <= LENGTH(id_price_types) - n.digit
                    ) input
                LEFT JOIN spy_price_product ON (
                    spy_price_product.fk_price_type = input.id_price_type AND spy_price_product.fk_product = input.id_product
                )
            )
            (
              SELECT
                    idPriceProduct,
                    id_price_type,
                    id_product
                FROM records
            )
            ON DUPLICATE KEY UPDATE
                fk_product = records.id_product,
                fk_price_type = records.id_price_type
            RETURNING id_price_product";

        $parameters = [
            $rowCount,
            $product,
            $priceType,
        ];

        $results = $this->propelExecutor->execute($sql, $parameters);

        foreach ($results as $priceProductData) {
            static::$priceProductIds[][static::KEY_ID_PRICE_PRODUCT] = (int)$priceProductData[static::KEY_ID_PRICE_PRODUCT];
        }
    }

    /**
     * @return void
     */
    protected function collectStoreIds(): void
    {
        $storeCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, self::COLUMN_STORE_NAME);

        $rowCount = count($storeCollection);
        $store = $this->dataFormatter->formatStringList($storeCollection, $rowCount);

        $sql = '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.store,
                  spy_store.id_store as id_store
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, \',\', n.digit + 1), \',\', -1), \'\') as store
                       FROM (
                            SELECT ? as stores
                       ) temp
                       INNER JOIN n
                            ON LENGTH(REPLACE(stores, \',\', \'\')) <= LENGTH(stores) - n.digit
                    ) input
                    LEFT JOIN spy_store ON spy_store.name = input.store
            ) SELECT records.id_store FROM records';

        $results = $this->propelExecutor->execute($sql, [
            $rowCount,
            $store,
        ]);

        foreach ($results as $storeData) {
            static::$storeIds[][static::KEY_ID_STORE] = (int)$storeData[static::KEY_ID_STORE];
        }
    }

    /**
     * @return void
     */
    protected function collectCurrencyIds(): void
    {
        $currencyCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, self::COLUMN_CURRENCY);

        $rowCount = count($currencyCollection);
        $currency = $this->dataFormatter->formatStringList($currencyCollection, $rowCount);

        $sql = '
            WITH RECURSIVE n(digit) AS (
                    SELECT 0 as digit
                    UNION ALL
                    SELECT 1 + digit FROM n WHERE digit < ?
                ), records AS (
                SELECT
                  input.currency,
                  spy_currency.id_currency as id_currency
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.currencies, \',\', n.digit + 1), \',\', -1), \'\') as currency
                       FROM (
                            SELECT ? as currencies
                       ) temp
                       INNER JOIN n
                            ON LENGTH(REPLACE(currencies, \',\', \'\')) <= LENGTH(currencies) - n.digit
                    ) input
                LEFT JOIN spy_currency ON spy_currency.code = input.currency
            ) SELECT records.id_currency FROM records;';

        $results = $this->propelExecutor->execute($sql, [
            $rowCount,
            $currency,
        ]);

        foreach ($results as $currencyData) {
            static::$currencyIds[][static::KEY_ID_CURRENCY] = (int)$currencyData[static::KEY_ID_CURRENCY];
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductStore(): void
    {
        $storeCollection = $this->dataFormatter->getCollectionDataByKey(static::$storeIds, static::KEY_ID_STORE);
        $rowCount = count($storeCollection);
        $store = $this->dataFormatter->formatStringList(
            $storeCollection,
            $rowCount
        );

        $currency = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$currencyIds, static::KEY_ID_CURRENCY),
            $rowCount
        );

        $productPrice = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductIds, static::KEY_ID_PRICE_PRODUCT),
            $rowCount
        );

        $grossPrice = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, static::COLUMN_GROSS_PRICE),
            $rowCount
        );
        $netPrice = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, static::COLUMN_NET_PRICE),
            $rowCount
        );
        $priceData = $this->dataFormatter->formatPriceStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, static::COLUMN_PRICE_DATA),
            $rowCount
        );
        $checksum = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, static::COLUMN_PRICE_DATA_CHECKSUM),
            $rowCount
        );
        $priceProductStoreKey = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductOfferStoreCollection, static::KEY_PRICE_PRODUCT_STORE_KEY)
        );

        $parameters = [
            $rowCount,
            $store,
            $currency,
            $productPrice,
            $grossPrice,
            $netPrice,
            $priceData,
            $checksum,
            $priceProductStoreKey,
        ];

        $sql = "
            INSERT INTO spy_price_product_store (
              id_price_product_store,
              fk_store,
              fk_currency,
              fk_price_product,
              net_price,
              gross_price,
              price_data,
              price_data_checksum,
              price_product_store_key
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                   input.id_store,
                   input.id_currency,
                   input.id_price_product,
                   input.net_price,
                   input.gross_price,
                   input.price_data,
                   input.price_data_checksum,
                   input.price_product_store_key,
                   spy_price_product_store.id_price_product_store as idPriceProductStore
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_stores, ',', n.digit + 1), ',', -1), '') as id_store,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_currencies, ',', n.digit + 1), ',', -1), '') as id_currency,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_price_products, ',', n.digit + 1), ',', -1), '') as id_price_product,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.gross_prices, ',', n.digit + 1), ',', -1), '') as gross_price,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.net_prices, ',', n.digit + 1), ',', -1), '') as net_price,
                            REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as price_data,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_data_checksums, ',', n.digit + 1), ',', -1), '') as price_data_checksum,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_product_store_keys, ',', n.digit + 1), ',', -1), '') as price_product_store_key
                       FROM (
                            SELECT ? as id_stores,
                                   ? as id_currencies,
                                   ? as id_price_products,
                                   ? as gross_prices,
                                   ? as net_prices,
                                   ? as price_datas,
                                   ? as price_data_checksums,
                                   ? as price_product_store_keys
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(id_stores, ',', '')) <= LENGTH(id_stores) - n.digit
                            AND LENGTH(REPLACE(id_currencies, ',', '')) <= LENGTH(id_currencies) - n.digit
                            AND LENGTH(REPLACE(id_price_products, ',', '')) <= LENGTH(id_price_products) - n.digit
                            AND LENGTH(REPLACE(gross_prices, ',', '')) <= LENGTH(gross_prices) - n.digit
                            AND LENGTH(REPLACE(price_datas, ',', '')) <= LENGTH(price_datas) - n.digit
                            AND LENGTH(REPLACE(price_data_checksums, ',', '')) <= LENGTH(price_data_checksums) - n.digit
                            AND LENGTH(REPLACE(net_prices, ',', '')) <= LENGTH(net_prices) - n.digit
                            AND LENGTH(REPLACE(price_product_store_keys, ',', '')) <= LENGTH(price_product_store_keys) - n.digit
                    ) input
                    LEFT JOIN spy_price_product_store ON (
                        spy_price_product_store.fk_price_product = input.id_price_product AND
                        spy_price_product_store.fk_currency = input.id_currency AND
                        spy_price_product_store.fk_store = input.id_store AND
                        spy_price_product_store.gross_price = input.gross_price AND
                        spy_price_product_store.net_price = input.net_price AND
                        spy_price_product_store.price_product_store_key = input.price_product_store_key
                    )
                )
                (
                SELECT
                    idPriceProductStore,
                    id_store,
                    id_currency,
                    id_price_product,
                    net_price,
                    gross_price,
                    price_data,
                    price_data_checksum,
                    price_product_store_key
                FROM records
                )
                ON DUPLICATE KEY UPDATE
                  gross_price = records.gross_price,
                  net_price = records.net_price,
                  price_data = records.price_data,
                  price_data_checksum = records.price_data_checksum
                RETURNING id_price_product_store";

        $results = $this->propelExecutor->execute($sql, $parameters);

        foreach ($results as $priceProductStoreData) {
            static::$priceProductStoreIds[][static::KEY_ID_PRICE_PRODUCT_STORE] = (int)$priceProductStoreData[static::KEY_ID_PRICE_PRODUCT_STORE];
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductOffer(): void
    {
        $productOfferCollection = $this->dataFormatter->getCollectionDataByKey(static::$productOfferIds, static::KEY_ID_PRODUCT_OFFER);
        $rowCount = count($productOfferCollection);
        $productOffer = $this->dataFormatter->formatStringList(
            $productOfferCollection,
            $rowCount
        );

        $priceProductStore = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductStoreIds, static::KEY_ID_PRICE_PRODUCT_STORE),
            $rowCount
        );

        $parameters = [
            $rowCount,
            $productOffer,
            $priceProductStore,
        ];

        $sql = '
            INSERT INTO spy_price_product_offer (
              id_price_product_offer,
              fk_price_product_store,
              fk_product_offer
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.productOffer,
                  input.priceProductStore,
                  spy_price_product_offer.id_price_product_offer as idPriceProductOffer
                FROM (
                   SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productOffers, \',\', n.digit + 1), \',\', -1), \'\') as productOffer,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.priceProductStores, \',\', n.digit + 1), \',\', -1), \'\') as priceProductStore
                   FROM (
                        SELECT ? as productOffers,
                               ? as priceProductStores
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(productOffers, \',\', \'\')) <= LENGTH(productOffers) - n.digit
                        AND LENGTH(REPLACE(priceProductStores, \',\', \'\')) <= LENGTH(priceProductStores) - n.digit
                 ) input
                LEFT JOIN spy_price_product_offer ON (
                    spy_price_product_offer.fk_product_offer = input.productOffer AND
                    spy_price_product_offer.fk_price_product_store = input.priceProductStore
                )
            )
            (
              SELECT
                idPriceProductOffer,
                priceProductStore,
                productOffer
              FROM records
            )
            ON DUPLICATE KEY UPDATE fk_product_offer = records.productOffer,
                fk_price_product_store = records.priceProductStore
            RETURNING id_price_product_offer';

        $results = $this->propelExecutor->execute($sql, $parameters);

        foreach ($results as $priceProductOfferData) {
            DataImporterPublisher::addEvent(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH, (int)$priceProductOfferData[static::KEY_ID_PRICE_PRODUCT_OFFER]);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectPriceProductOfferCollection(DataSetInterface $dataSet): void
    {
        if (isset($dataSet[static::IS_AFFILIATE_KEY]) && $dataSet[static::IS_AFFILIATE_KEY] === 'TRUE') {
            $priceProductOfferStore = [
                static::COLUMN_PRICE_TYPE => $dataSet[static::KEY_PRICE_TYPE] ?: 'DEFAULT',
                static::COLUMN_STORE_NAME => $dataSet[static::KEY_STORE_NAME],
                static::COLUMN_CURRENCY => $dataSet[static::KEY_CURRENCY],
                static::COLUMN_NET_PRICE => $this->getPrice($dataSet),
                static::COLUMN_GROSS_PRICE => $this->getPrice($dataSet),
                static::COLUMN_CONCRETE_SKU => $dataSet[static::KEY_CONCRETE_SKU],
                static::COLUMN_MERCHANT_REFERENCE => $dataSet[static::KEY_MERCHANT_REFERENCE],
                static::COLUMN_PRODUCT_OFFER_REFERENCE => $this->getProductOfferReferenceKey($dataSet),
                static::KEY_PRICE_PRODUCT_STORE_KEY => $this->getProductOfferReferenceKey($dataSet),
                static::COLUMN_PRICE_DATA => '',
                static::COLUMN_PRICE_DATA_CHECKSUM => '',
            ];

            $priceData = $this->getPriceData($dataSet);
            $priceProductOfferStore[static::COLUMN_PRICE_DATA] = $priceData[static::COLUMN_VOLUME_PRICES] !== null ? $this->utilEncodingService->encodeJson($priceData) : null;
            $priceProductOfferStore[static::COLUMN_PRICE_DATA_CHECKSUM] = $priceData[static::COLUMN_VOLUME_PRICES] !== null ? $this->priceProductFacade
                ->generatePriceDataChecksum($priceData) : null;

            static::$priceProductOfferStoreCollection[] = $priceProductOfferStore;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return float|null
     */
    protected function getPrice(DataSetInterface $dataSet): ?float
    {
        if (!empty($dataSet[static::KEY_VALUE_GROSS])) {
            return (int)((string)((float)str_replace(',', '.', $dataSet[static::KEY_VALUE_GROSS]) * 100));
        }

        return null;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function getProductOfferReferenceKey(DataSetInterface $dataSet): string
    {
        return sprintf(
            '%s-%s',
            $dataSet[static::KEY_MERCHANT_REFERENCE],
            $dataSet[static::KEY_CONCRETE_SKU]
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array
     */
    protected function getPriceData(DataSetInterface $dataSet): array
    {
        $volumePrices = $this->utilEncodingService->decodeJson(
            $dataSet[static::KEY_VOLUME_PRICES],
            true
        );

        return [
            static::COLUMN_VOLUME_PRICES => $volumePrices,
        ];
    }
}
