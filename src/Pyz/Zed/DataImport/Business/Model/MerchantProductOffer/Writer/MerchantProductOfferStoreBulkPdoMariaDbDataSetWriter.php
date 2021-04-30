<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\MerchantProductOffer\Writer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferStoreEvents;

class MerchantProductOfferStoreBulkPdoMariaDbDataSetWriter implements DataSetWriterInterface
{
    public const BULK_SIZE = 100;

    protected const MERCHANT_SKU = 'merchant_sku';
    protected const IS_ACTIVE = 'is_active';
    protected const APPROVAL_STATUS = 'approval_status';
    protected const ID_MERCHANT = 'id_merchant';
    protected const ID_PRODUCT_OFFER = 'id_product_offer';
    protected const ID_STORE = 'id_store';

    protected const COLUMN_STORE_NAME = 'store_name';
    protected const COLUMN_CONCRETE_SKU = 'concrete_sku';
    protected const COLUMN_MERCHANT_REFERENCE = 'merchant_reference';

    protected const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    protected const KEY_STORE_NAME = 'product_abstract_store.store_name';
    protected const KEY_MERCHANT_REFERENCE = 'product.value_10';
    protected const KEY_CONCRETE_SKU = 'concrete_sku';

    protected const IS_AFFILIATE_KEY = 'product.value_73';

    /**
     * @var array
     */
    protected static $merchantProductOfferStoreCollection = [];

    /**
     * @var \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected static $entityEventTransfers = [];

    /**
     * @var array
     */
    protected static $merchantProductOfferIdsCollection = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportToEventFacadeInterface $eventFacade
    ) {
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        $this->collectMerchantProductOfferStoreCollection($dataSet);

        if (count(static::$merchantProductOfferStoreCollection) >= static::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        if (static::$merchantProductOfferStoreCollection === []) {
            return;
        }

        $this->persistProductOfferStore();
        $this->collectProductOfferEvents();

        $this->eventFacade->triggerBulk(MerchantProductOfferStoreEvents::MERCHANT_PRODUCT_OFFER_STORE_KEY_PUBLISH, static::$entityEventTransfers);

        static::$merchantProductOfferStoreCollection = [];
        static::$merchantProductOfferIdsCollection = [];
        static::$entityEventTransfers = [];
    }

    /**
     * @return void
     */
    protected function persistProductOfferStore(): void
    {
        $storeNames = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferStoreCollection, static::COLUMN_STORE_NAME)
        );
        $productOfferReferences = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferStoreCollection, static::PRODUCT_OFFER_REFERENCE)
        );

        $sql = '
            INSERT INTO spy_product_offer_store (
              fk_store,
              fk_product_offer
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.store,
                  input.offer,
                  spy_store.id_store as idStore,
                  spy_product_offer.id_product_offer as idProductOffer
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, \',\', n.digit + 1), \',\', -1), \'\') as store,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.offers, \',\', n.digit + 1), \',\', -1), \'\') as offer
                   FROM (
                        SELECT ? as stores,
                               ? as offers
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(stores, \',\', \'\')) <= LENGTH(stores) - n.digit
                        AND LENGTH(REPLACE(offers, \',\', \'\')) <= LENGTH(offers) - n.digit
                 ) input
                INNER JOIN spy_store ON spy_store.name = input.store
                INNER JOIN spy_product_offer ON spy_product_offer.product_offer_reference = input.offer
            )
            (
              SELECT
                idStore,
                idProductOffer
              FROM records
            )
            ON DUPLICATE KEY UPDATE fk_store = records.idStore
            RETURNING fk_product_offer as id_product_offer';

        $parameters = [
            count(static::$merchantProductOfferStoreCollection),
            $storeNames,
            $productOfferReferences,
        ];

        $resultProductOffer = $this->propelExecutor->execute($sql, $parameters);

        foreach ($resultProductOffer as $productOfferData) {
            static::$merchantProductOfferIdsCollection[][static::ID_PRODUCT_OFFER] = $productOfferData[static::ID_PRODUCT_OFFER];
        }
    }

    /**
     * @return void
     */
    protected function collectProductOfferEvents(): void
    {
        $productOfferIds = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferIdsCollection, static::ID_PRODUCT_OFFER)
        );

        $sql = ' WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.productOfferId,
                  spy_product_offer.id_product_offer as id_product_offer,
                  spy_product_offer.concrete_sku as concrete_sku,
                  spy_product_offer.product_offer_reference as product_offer_reference
                FROM
                    (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productOfferIds, \',\', n.digit + 1), \',\', -1), \'\') as productOfferId
                       FROM (
                            SELECT ? as productOfferIds
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(productOfferIds, \',\', \'\')) <= LENGTH(productOfferIds) - n.digit
                    ) input
                    LEFT JOIN spy_product_offer ON spy_product_offer.id_product_offer = input.productOfferId
            ) SELECT records.id_product_offer,concrete_sku,product_offer_reference FROM records';

        $parameters = [
            count(static::$merchantProductOfferIdsCollection),
            $productOfferIds,
        ];

        $productOfferResult = $this->propelExecutor->execute($sql, $parameters);

        foreach ($productOfferResult as $productOfferData) {
            $this->addPublishEvent($productOfferData);
        }
    }

    /**
     * @param array $productOfferData
     *
     * @return void
     */
    protected function addPublishEvent(array $productOfferData): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($productOfferData[static::ID_PRODUCT_OFFER]);
        $eventEntityTransfer->setAdditionalValues([
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferData[static::PRODUCT_OFFER_REFERENCE],
            SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferData[static::COLUMN_CONCRETE_SKU],
        ]);

        static::$entityEventTransfers[] = $eventEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectMerchantProductOfferStoreCollection(DataSetInterface $dataSet): void
    {
        if (isset($dataSet[static::IS_AFFILIATE_KEY]) && $dataSet[static::IS_AFFILIATE_KEY] === 'TRUE') {
            static::$merchantProductOfferStoreCollection[] = [
                static::COLUMN_STORE_NAME => $dataSet[static::KEY_STORE_NAME],
                static::PRODUCT_OFFER_REFERENCE => $this->getProductOfferReference($dataSet),
            ];
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function getProductOfferReference(DataSetInterface $dataSet): string
    {
        return sprintf(
            '%s-%s',
            $dataSet[static::KEY_MERCHANT_REFERENCE],
            $dataSet[static::KEY_CONCRETE_SKU]
        );
    }
}
