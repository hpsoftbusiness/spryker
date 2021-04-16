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
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;

class MerchantProductOfferBulkPdoMariaDbDataSetWriter implements DataSetWriterInterface
{
    public const BULK_SIZE = 100;

    protected const STORE_NAME = 'store_name';
    protected const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    protected const CONCRETE_SKU = 'concrete_sku';
    protected const MERCHANT_REFERENCE = 'merchant_reference';
    protected const MERCHANT_SKU = 'merchant_sku';
    protected const IS_ACTIVE = 'is_active';
    protected const APPROVAL_STATUS = 'approval_status';
    protected const ID_MERCHANT = 'id_merchant';
    protected const ID_PRODUCT_OFFER = 'id_product_offer';
    protected const ID_STORE = 'id_store';

    protected const KEY_MERCHANT_REFERENCE = 'product.value_10';

    protected const AFFILIATE_DATA_KEY = 'affiliate_data';

    /**
     * @var array
     */
    protected static $merchantProductOfferCollection = [];

    /**
     * @var \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected static $entityEventTransfers = [];

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
        $this->collectMerchantProductOfferCollection($dataSet);

        if (count(static::$merchantProductOfferCollection) >= static::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->persistProductOffer();

        $this->eventFacade->triggerBulk(MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_PUBLISH, static::$entityEventTransfers);

        static::$entityEventTransfers = [];
        static::$merchantProductOfferCollection = [];
    }

    /**
     * @return void
     */
    protected function persistProductOffer(): void
    {
        $productOfferReferences = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::PRODUCT_OFFER_REFERENCE)
        );
        $merchantIds = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::ID_MERCHANT)
        );
        $merchantSkus = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::MERCHANT_SKU)
        );
        $concreteSkus = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::CONCRETE_SKU)
        );
        $statuses = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::IS_ACTIVE)
        );
        $approvalStatuses = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::APPROVAL_STATUS)
        );

        $affiliateDatas = $this->dataFormatter->formatPriceStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$merchantProductOfferCollection, static::AFFILIATE_DATA_KEY)
        );

        $parameters = [
            count(static::$merchantProductOfferCollection),
            $merchantIds,
            $approvalStatuses,
            $concreteSkus,
            $statuses,
            $merchantSkus,
            $productOfferReferences,
            $affiliateDatas,
        ];

        $sql = '
            INSERT INTO spy_product_offer (
              fk_merchant,
              approval_status,
              concrete_sku,
              is_active,
              merchant_sku,
              product_offer_reference,
              affiliate_data
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.merchantId,
                  input.approvalStatus,
                  input.concreteSku,
                  input.status,
                  input.merchantSku,
                  input.productOfferReference,
                  input.affiliateData,
                  spy_product_offer.id_product_offer as idProductOffer
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.merchantIds, \',\', n.digit + 1), \',\', -1), \'\') as merchantId,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.approvalStatuses, \',\', n.digit + 1), \',\', -1), \'\') as approvalStatus,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.concreteSkus, \',\', n.digit + 1), \',\', -1), \'\') as concreteSku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.statuses, \',\', n.digit + 1), \',\', -1), \'\') as status,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.merchantSkus, \',\', n.digit + 1), \',\', -1), \'\') as merchantSku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productOfferReferences, \',\', n.digit + 1), \',\', -1), \'\') as productOfferReference,
                        REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.affiliateDatas, \',\', n.digit + 1), \',\', -1), \'\'), \'|\', \',\') as affiliateData
                   FROM (
                        SELECT ? as merchantIds,
                               ? as approvalStatuses,
                               ? as concreteSkus,
                               ? as statuses,
                               ? as merchantSkus,
                               ? as productOfferReferences,
                               ? as affiliateDatas
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(merchantIds, \',\', \'\')) <= LENGTH(merchantIds) - n.digit
                        AND LENGTH(REPLACE(approvalStatuses, \',\', \'\')) <= LENGTH(approvalStatuses) - n.digit
                        AND LENGTH(REPLACE(approvalStatuses, \',\', \'\')) <= LENGTH(approvalStatuses) - n.digit
                        AND LENGTH(REPLACE(concreteSkus, \',\', \'\')) <= LENGTH(concreteSkus) - n.digit
                        AND LENGTH(REPLACE(statuses, \',\', \'\')) <= LENGTH(statuses) - n.digit
                        AND LENGTH(REPLACE(merchantSkus, \',\', \'\')) <= LENGTH(merchantSkus) - n.digit
                        AND LENGTH(REPLACE(productOfferReferences, \',\', \'\')) <= LENGTH(productOfferReferences) - n.digit
                        AND LENGTH(REPLACE(affiliateDatas, \',\', \'\')) <= LENGTH(affiliateDatas) - n.digit
                 ) input
                LEFT JOIN spy_product_offer ON spy_product_offer.product_offer_reference = input.productOfferReference
            )
            (
              SELECT
                merchantId,
                approvalStatus,
                concreteSku,
                status,
                merchantSku,
                productOfferReference,
                affiliateData
              FROM records
            )
            ON DUPLICATE KEY UPDATE approval_status = records.approvalStatus,
                is_active = records.status,
                affiliate_data = records.affiliateData,
                updated_at = now()
            RETURNING id_product_offer, concrete_sku, product_offer_reference';

        $resultProductOffer = $this->propelExecutor->execute($sql, $parameters);

        foreach ($resultProductOffer as $productOfferData) {
            $this->addPublishEvent($productOfferData);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectMerchantProductOfferCollection(DataSetInterface $dataSet): void
    {
        static::$merchantProductOfferCollection[] = [
            static::PRODUCT_OFFER_REFERENCE => $this->getProductOfferReference($dataSet),
            static::CONCRETE_SKU => $dataSet[static::CONCRETE_SKU],
            static::MERCHANT_REFERENCE => $dataSet[static::KEY_MERCHANT_REFERENCE],
            static::MERCHANT_SKU => $dataSet[static::MERCHANT_SKU] ?? null,
            static::ID_MERCHANT => $dataSet[static::ID_MERCHANT],
            static::IS_ACTIVE => true,
            static::APPROVAL_STATUS => $dataSet[static::APPROVAL_STATUS] ?? ProductOfferConfig::STATUS_APPROVED,
            static::AFFILIATE_DATA_KEY => json_encode($dataSet[static::AFFILIATE_DATA_KEY]),
        ];
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
            $dataSet[static::CONCRETE_SKU]
        );
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
            SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferData[static::CONCRETE_SKU],
        ]);

        static::$entityEventTransfers[] = $eventEntityTransfer;
    }
}
