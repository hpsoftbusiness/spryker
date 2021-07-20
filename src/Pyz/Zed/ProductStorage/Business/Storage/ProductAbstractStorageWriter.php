<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Propel;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface;
use Pyz\Zed\ProductStorage\ProductStorageConfig;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface;
use Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriter as SprykerProductAbstractStorageWriter;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;

class ProductAbstractStorageWriter extends SprykerProductAbstractStorageWriter
{
    public const COL_FK_PRODUCT_SET = 'fk_product_set';

    protected const BULK_SIZE = 100;

    /**
     * @var \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @var array
     */
    protected $synchronizedDataCollection = [];

    /**
     * @var array
     */
    protected $synchronizedMessageCollection = [];

    /**
     * @var \Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface[]
     */
    protected $productAbstractStorageExpanderPlugins = [];

    /**
     * @var \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    protected $productAbstractStorageCte;

    /**
     * @var \Pyz\Zed\ProductStorage\ProductStorageConfig
     */
    private $config;

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface $attributeMap
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     * @param \Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface[] $productAbstractStorageExpanderPlugins
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface $productAbstractStorageCte
     * @param \Pyz\Zed\ProductStorage\ProductStorageConfig $config
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        AttributeMapInterface $attributeMap,
        ProductStorageQueryContainerInterface $queryContainer,
        ProductStorageToStoreFacadeInterface $storeFacade,
        $isSendingToQueue,
        array $productAbstractStorageExpanderPlugins,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        ProductStorageCteStrategyInterface $productAbstractStorageCte,
        ProductStorageConfig $config
    ) {
        parent::__construct(
            $productFacade,
            $attributeMap,
            $queryContainer,
            $storeFacade,
            $isSendingToQueue,
            $productAbstractStorageExpanderPlugins
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->productAbstractStorageCte = $productAbstractStorageCte;
        $this->config = $config;
    }

    /**
     * @param array $productAbstractLocalizedEntities
     * @param \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage[] $productAbstractStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productAbstractLocalizedEntities, array $productAbstractStorageEntities)
    {
        $pairedEntities = $this->pairProductAbstractLocalizedEntitiesWithProductAbstractStorageEntities(
            $productAbstractLocalizedEntities,
            $productAbstractStorageEntities
        );

        $attributeMapBulk = $this->attributeMap->generateAttributeMapBulk(
            array_column($productAbstractLocalizedEntities, static::COL_FK_PRODUCT_ABSTRACT),
            array_column($productAbstractLocalizedEntities, static::COL_FK_LOCALE)
        );

        foreach ($pairedEntities as $pair) {
            $productAbstractLocalizedEntity = $pair[static::PRODUCT_ABSTRACT_LOCALIZED_ENTITY];
            $productAbstractStorageEntity = $pair[static::PRODUCT_ABSTRACT_STORAGE_ENTITY];

            if ($productAbstractLocalizedEntity === null || !$this->isActive($productAbstractLocalizedEntity)) {
                $this->deleteProductAbstractStorageEntity($productAbstractStorageEntity);

                continue;
            }

            if (in_array($pair[static::LOCALE_NAME], $this->config->getLocalsByStore($pair[static::STORE_NAME]))) {
                $this->addProductAbstractStorageEntity(
                    $productAbstractLocalizedEntity,
                    $pair[static::STORE_NAME],
                    $pair[static::LOCALE_NAME],
                    $attributeMapBulk
                );
            }
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.product', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param string $storeName
     * @param string $localeName
     * @param array $attributeMapBulk
     *
     * @return void
     */
    protected function addProductAbstractStorageEntity(
        array $productAbstractLocalizedEntity,
        $storeName,
        $localeName,
        array $attributeMapBulk = []
    ): void {
        $productAbstractStorageTransfer = $this->mapToProductAbstractStorageTransfer(
            $productAbstractLocalizedEntity,
            new ProductAbstractStorageTransfer(),
            $attributeMapBulk
        );

        $productAbstractStorageData = [
            'fk_product_abstract' => $productAbstractLocalizedEntity['SpyProductAbstract'][static::COL_ID_PRODUCT_ABSTRACT],
            'data' => $productAbstractStorageTransfer->toArray(),
            'store' => $storeName,
            'locale' => $localeName,
        ];

        $this->add($productAbstractStorageData);
    }

    /**
     * @param array $productAbstractStorageData
     *
     * @return void
     */
    protected function add(array $productAbstractStorageData): void
    {
        $synchronizedData = $this->buildSynchronizedData($productAbstractStorageData, 'fk_product_abstract', 'product_abstract');
        $this->synchronizedDataCollection[] = $synchronizedData;

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'product_abstract');
        }
    }

    /**
     * @param array $data
     * @param string $keySuffix
     * @param string $resourceName
     *
     * @return array
     */
    public function buildSynchronizedData(array $data, string $keySuffix, string $resourceName): array
    {
        $key = $this->generateResourceKey($data, $keySuffix, $resourceName);
        $encodedData = json_encode($data['data']);
        $data['key'] = $key;
        $data['data'] = $encodedData;

        return $data;
    }

    /**
     * @param array $data
     * @param string $keySuffix
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateResourceKey(array $data, string $keySuffix, string $resourceName)
    {
        $syncTransferData = new SynchronizationDataTransfer();
        if (isset($data['store'])) {
            $syncTransferData->setStore($data['store']);
        }

        if (isset($data['locale'])) {
            $syncTransferData->setLocale($data['locale']);
        }

        $syncTransferData->setReference($data[$keySuffix]);
        $keyBuilder = $this->synchronizationService->getStorageKeyBuilder($resourceName);

        return $keyBuilder->generateKey($syncTransferData);
    }

    /**
     * @param array $data
     * @param string $resourceName
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function buildSynchronizedMessage(
        array $data,
        string $resourceName,
        array $params = []
    ): QueueSendMessageTransfer {
        $data['_timestamp'] = microtime(true);
        $payload = [
            'write' => [
                'key' => $data['key'],
                'value' => $data['data'],
                'resource' => $resourceName,
                'params' => $params,
            ],
        ];

        $queueSendTransfer = new QueueSendMessageTransfer();
        $queueSendTransfer->setBody(json_encode($payload));

        if (isset($data['store'])) {
            $queueSendTransfer->setStoreName($data['store']);

            return $queueSendTransfer;
        }

        $queueSendTransfer->setQueuePoolName('synchronizationPool');

        return $queueSendTransfer;
    }

    /**
     * @return void
     */
    public function write()
    {
        if (empty($this->synchronizedDataCollection)) {
            return;
        }

        $publishData = [];

        foreach ($this->synchronizedDataCollection as $data) {
            $publishData[] = $data;

            if (count($publishData) >= static::BULK_SIZE) {
                $this->executePublishData($publishData);

                $publishData = [];
            }
        }
        if (count($publishData) > 0) {
            $this->executePublishData($publishData);
        }

        $this->synchronizedDataCollection = [];
    }

    /**
     * @param mixed $publishData
     *
     * @return void
     */
    protected function executePublishData($publishData): void
    {
        $stmt = Propel::getConnection()->prepare($this->productAbstractStorageCte->getSql());
        $stmt->execute($this->productAbstractStorageCte->buildParams($publishData));
    }

    /**
     * @param array $productAbstractLocalizedEntity
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     * @param array $attributeMapBulk
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function mapToProductAbstractStorageTransfer(
        array $productAbstractLocalizedEntity,
        ProductAbstractStorageTransfer $productAbstractStorageTransfer,
        array $attributeMapBulk = []
    ) {
        $productAbstractStorageTransfer = parent::mapToProductAbstractStorageTransfer($productAbstractLocalizedEntity, $productAbstractStorageTransfer, $attributeMapBulk);

        $productAbstractStorageTransfer->setProductSetIds([]);

        if (isset($productAbstractLocalizedEntity['SpyProductAbstract']['SpyProductAbstractSets'])) {
            $productSetIds = [];
            foreach ($productAbstractLocalizedEntity['SpyProductAbstract']['SpyProductAbstractSets'] as $productAbstractSet) {
                if (isset($productAbstractSet[static::COL_FK_PRODUCT_SET])) {
                    $productSetIds[] = $productAbstractSet[static::COL_FK_PRODUCT_SET];
                }
            }

            $productAbstractStorageTransfer->setProductSetIds($productSetIds);
        }

        $productAbstractStorageTransfer
            ->setAffiliateData(
                $this->productFacade->decodeProductAttributes($productAbstractLocalizedEntity['SpyProductAbstract']['affiliate_data'])
            );

        return $productAbstractStorageTransfer;
    }
}
