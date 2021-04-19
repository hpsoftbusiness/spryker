<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage;
use Propel\Runtime\Propel;
use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriter as SprykerPriceProductConcreteStorageWriter;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;

class PriceProductConcreteStorageWriter extends SprykerPriceProductConcreteStorageWriter
{
    use MariaDbDataFormatterTrait;

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
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(
        PriceProductStorageToPriceProductFacadeInterface $priceProductFacade,
        PriceProductStorageToStoreFacadeInterface $storeFacade,
        PriceProductStorageQueryContainerInterface $queryContainer,
        bool $isSendingToQueue,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient
    ) {
        parent::__construct($priceProductFacade, $storeFacade, $queryContainer, $isSendingToQueue);

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
    }

    /**
     * @param array $priceGroups First level keys are product concrete ids, second level keys are store names, values are grouped prices.
     * @param array $priceProductConcreteStorageMap First level keys are product concrete ids, second level keys are store names, values are SpyPriceProductConcreteStorage objects
     *
     * @return void
     */
    protected function storeData(array $priceGroups, array $priceProductConcreteStorageMap)
    {
        foreach ($priceGroups as $idProductConcrete => $storePriceGroups) {
            foreach ($storePriceGroups as $storeName => $priceGroup) {
                $priceProductConcreteStorage = $this->getRelatedPriceProductConcreteStorageEntity(
                    $priceProductConcreteStorageMap,
                    $idProductConcrete,
                    $storeName
                );

                unset($priceProductConcreteStorageMap[$idProductConcrete][$storeName]);

                if ($this->hasProductConcretePrices($priceGroup)) {
                    $this->createPriceProductStorage(
                        $idProductConcrete,
                        $storeName,
                        $priceGroup
                    );

                    continue;
                }

                $this->deletePriceProduct($priceProductConcreteStorage);
            }
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.price', $this->synchronizedMessageCollection);
        }

        array_walk_recursive($priceProductConcreteStorageMap, function (SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity) {
            $priceProductConcreteStorageEntity->delete();
        });
    }

    /**
     * @param int $idProductConcrete
     * @param string $storeName
     * @param array $priceGroup
     *
     * @return void
     */
    protected function createPriceProductStorage(
        $idProductConcrete,
        $storeName,
        array $priceGroup
    ) {
        $priceProductStorageTransfer = (new PriceProductStorageTransfer())
            ->setPrices($priceGroup);

        $priceProductConcreteStorageData = [
            'fk_product' => $idProductConcrete,
            'data' => $priceProductStorageTransfer->toArray(),
            'store' => $storeName,
        ];

        $this->add($priceProductConcreteStorageData);
    }

    /**
     * @param array $productAbstractStorageData
     *
     * @return void
     */
    protected function add(array $productAbstractStorageData): void
    {
        $synchronizedData = $this->buildSynchronizedData($productAbstractStorageData, 'fk_product', 'price_product_concrete');
        $this->synchronizedDataCollection[] = $synchronizedData;

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'price_product_concrete');
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

        $parameter = $this->collectMultiInsertData(
            $this->synchronizedDataCollection
        );

        $sql = 'INSERT INTO `spy_price_product_concrete_storage` (`fk_product`, `data`, `store`, `key`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE `fk_product`=values(`fk_product`), `data`=values(`data`), `store`=values(`store`), `key`=values(`key`);';

        $connection = Propel::getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();

        $this->synchronizedDataCollection = [];
    }
}
