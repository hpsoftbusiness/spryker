<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business\Storage;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorage;
use Propel\Runtime\Propel;
use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriter as SprykerUrlStorageWriter;
use Spryker\Zed\UrlStorage\Dependency\Facade\UrlStorageToStoreFacadeInterface;
use Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\UrlStorage\Persistence\UrlStorageEntityManagerInterface;
use Spryker\Zed\UrlStorage\Persistence\UrlStorageRepositoryInterface;

class UrlStorageWriter extends SprykerUrlStorageWriter
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
     * @param \Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface $utilSanitize
     * @param \Spryker\Zed\UrlStorage\Persistence\UrlStorageRepositoryInterface $urlStorageRepository
     * @param \Spryker\Zed\UrlStorage\Persistence\UrlStorageEntityManagerInterface $urlStorageEntityManager
     * @param \Spryker\Zed\UrlStorage\Dependency\Facade\UrlStorageToStoreFacadeInterface $storeFacade
     * @param bool $isSendingToQueue
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(
        UrlStorageToUtilSanitizeServiceInterface $utilSanitize,
        UrlStorageRepositoryInterface $urlStorageRepository,
        UrlStorageEntityManagerInterface $urlStorageEntityManager,
        UrlStorageToStoreFacadeInterface $storeFacade,
        bool $isSendingToQueue,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient
    ) {
        parent::__construct($utilSanitize, $urlStorageRepository, $urlStorageEntityManager, $storeFacade, $isSendingToQueue);

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
    }

    /**
     * @param array $urlStorageTransfers
     * @param array $urlStorageEntities
     *
     * @return void
     */
    protected function storeData(array $urlStorageTransfers, array $urlStorageEntities)
    {
        foreach ($urlStorageTransfers as $urlStorageTransfer) {
            $urlStorageEntity = $urlStorageEntities[$urlStorageTransfer->getIdUrl()] ?? null;

            $this->storeDataSet($urlStorageTransfer, $urlStorageEntity);
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.url', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param \Orm\Zed\UrlStorage\Persistence\SpyUrlStorage|null $urlStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(UrlStorageTransfer $urlStorageTransfer, ?SpyUrlStorage $urlStorageEntity = null)
    {
        $resource = $this->findResourceArguments($urlStorageTransfer->toArray());

        if ($resource === null) {
            return;
        }

        $urlStorage = [
            'resources' => [
                'fk_' . $resource[static::RESOURCE_TYPE] => $resource[static::RESOURCE_VALUE],
            ],
            'url' => $urlStorageTransfer->getUrl(),
            'fk_url' => $urlStorageTransfer->getIdUrl(),
            'data' => $urlStorageTransfer->modifiedToArray(),
        ];

        $this->add($urlStorage);
    }

    /**
     * @param array $urlStorage
     *
     * @return void
     */
    protected function add(array $urlStorage): void
    {
        $synchronizedData = $this->buildSynchronizedData($urlStorage, 'url', 'url');
        $this->synchronizedDataCollection[] = [
            'fk_url' => $synchronizedData['fk_url'],
            'url' => $synchronizedData['url'],
            'data' => $synchronizedData['data'],
            'key' => $synchronizedData['key'],
        ];

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'url');
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
    protected function generateResourceKey(array $data, string $keySuffix, string $resourceName): string
    {
        $syncTransferData = new SynchronizationDataTransfer();

        $syncTransferData->setReference($data[$keySuffix]);
        $syncTransferData->setLocale($data['data']['locale_name']);
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

        $sql = 'INSERT INTO `spy_url_storage` (`fk_url`, `url`, `data`, `key`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE `fk_url`=values(`fk_url`), `url`=values(`url`), `data`=values(`data`), `key`=values(`key`);';

        $connection = Propel::getConnection();
        $statement = $connection->prepare($sql);
        $statement->execute();

        $this->synchronizedDataCollection = [];
    }
}
