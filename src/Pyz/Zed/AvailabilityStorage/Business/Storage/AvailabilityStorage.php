<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityStorage\Business\Storage;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;
use Propel\Runtime\Propel;
use Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageCteInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorage as SprykerAvailabilityStorage;
use Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface;
use Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageRepositoryInterface;

class AvailabilityStorage extends SprykerAvailabilityStorage
{
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
     * @var \Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageCteInterface
     */
    protected $availabilityStorageCte;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     * @param \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageRepositoryInterface $availabilityStorageRepository
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param \Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageCteInterface $availabilityStorageCte
     */
    public function __construct(
        Store $store,
        AvailabilityStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue,
        AvailabilityStorageRepositoryInterface $availabilityStorageRepository,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient,
        AvailabilityStorageCteInterface $availabilityStorageCte
    ) {
        parent::__construct($store, $queryContainer, $isSendingToQueue, $availabilityStorageRepository);

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
        $this->availabilityStorageCte = $availabilityStorageCte;
    }

    /**
     * @param array $availabilityEntities
     * @param array $availabilityStorageEntityCollection
     *
     * @return void
     */
    protected function storeData(array $availabilityEntities, array $availabilityStorageEntityCollection)
    {
        foreach ($availabilityEntities as $availability) {
            $idAvailability = $availability[static::ID_AVAILABILITY_ABSTRACT];
            $storeName = $availability[static::STORE][static::STORE_NAME];

            if ($this->isExistingEntity($availabilityStorageEntityCollection, $idAvailability, $storeName)) {
                $this->storeDataSet($availability, $availabilityStorageEntityCollection[$idAvailability]);

                continue;
            }

            $this->storeDataSet($availability);
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.storage.availability', $this->synchronizedMessageCollection);
        }
    }

    /**
     * @param array $availability
     * @param \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage|null $availabilityStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $availability, ?SpyAvailabilityStorage $availabilityStorageEntity = null)
    {
        $availabilityStorage = [
            'fk_product_abstract' => $availability[static::ID_PRODUCT_ABSTRACT],
            'fk_availability_abstract' => $availability[static::ID_AVAILABILITY_ABSTRACT],
            'data' => $availability,
            'store' => $availability[static::STORE][static::STORE_NAME],
        ];

        $this->add($availabilityStorage);
    }

    /**
     * @param array $urlStorage
     *
     * @return void
     */
    protected function add(array $urlStorage): void
    {
        $synchronizedData = $this->buildSynchronizedData($urlStorage, 'fk_product_abstract', 'availability');
        $this->synchronizedDataCollection[] = $synchronizedData;

        if ($this->isSendingToQueue) {
            $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'availability');
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

        if (isset($data['store'])) {
            $syncTransferData->setLocale($data['store']);
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

        $stmt = Propel::getConnection()->prepare($this->getSql());
        $stmt->execute($this->getParams());
    }

    /**
     * @return string
     */
    protected function getSql(): string
    {
        return $this->availabilityStorageCte->getSql();
    }

    /**
     * @return array
     */
    protected function getParams(): array
    {
        return $this->availabilityStorageCte->buildParams($this->synchronizedDataCollection);
    }
}
