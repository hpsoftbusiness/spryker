<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Propel\Runtime\Propel;
use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher as SprykerProductConcretePageSearchPublisher;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchConfig;

class ProductConcretePageSearchPublisher extends SprykerProductConcretePageSearchPublisher
{
    use MariaDbDataFormatterTrait;

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
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface $productConcretePageSearchReader
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface $productFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper $productConcreteSearchDataMapper
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig $productPageSearchConfig
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(
        ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter,
        ProductPageSearchToProductInterface $productFacade,
        ProductPageSearchToUtilEncodingInterface $utilEncoding,
        AbstractProductSearchDataMapper $productConcreteSearchDataMapper,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        ProductPageSearchConfig $productPageSearchConfig,
        array $pageDataExpanderPlugins,
        SynchronizationServiceInterface $synchronizationService,
        QueueClientInterface $queueClient
    ) {
        parent::__construct(
            $productConcretePageSearchReader,
            $productConcretePageSearchWriter,
            $productFacade,
            $utilEncoding,
            $productConcreteSearchDataMapper,
            $storeFacade,
            $productPageSearchConfig,
            $pageDataExpanderPlugins
        );

        $this->synchronizationService = $synchronizationService;
        $this->queueClient = $queueClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(
        array $productConcreteTransfers,
        array $productConcretePageSearchTransfers
    ): void {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
                $this->syncProductConcretePageSearchPerStore(
                    $productConcreteTransfer,
                    $storeTransfer,
                    $productConcretePageSearchTransfers[$productConcreteTransfer->getIdProductConcrete()][$storeTransfer->getName()] ?? []
                );
            }
        }

        $this->write();

        if ($this->synchronizedMessageCollection !== []) {
            $this->queueClient->sendMessages('sync.search.product', $this->synchronizedMessageCollection);
            $this->synchronizedMessageCollection = [];
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return void
     */
    protected function syncProductConcretePageSearchPerLocale(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer,
        ProductConcretePageSearchTransfer $productConcretePageSearchTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): void {
        if (!$productConcreteTransfer->getIsActive() && $productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
            $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);

            return;
        }

        if (!$this->isValidStoreLocale($storeTransfer->getName(), $localizedAttributesTransfer->getLocale()->getLocaleName())) {
            if ($productConcretePageSearchTransfer->getIdProductConcretePageSearch() !== null) {
                $this->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
            }

            return;
        }

        $this->mapProductConcretePageSearchTransfer(
            $productConcreteTransfer,
            $storeTransfer,
            $productConcretePageSearchTransfer,
            $localizedAttributesTransfer
        );

        $this->add($productConcretePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productPageSearchTransfer
     *
     * @return void
     */
    protected function add(ProductConcretePageSearchTransfer $productPageSearchTransfer): void
    {
        $synchronizedData = $this->buildSynchronizedData($productPageSearchTransfer, 'product_concrete');
        $this->synchronizedDataCollection[] = [
            'fk_product' => $synchronizedData['fk_product'],
            'store' => $synchronizedData['store'],
            'locale' => $synchronizedData['locale'],
            'data' => addslashes($synchronizedData['data']),
            'structured_data' => addslashes($synchronizedData['structured_data']),
            'key' => $synchronizedData['key'],
        ];

        $this->synchronizedMessageCollection[] = $this->buildSynchronizedMessage($synchronizedData, 'product_concrete', ['type' => 'page']);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productPageSearchTransfer
     * @param string $resourceName
     *
     * @return array
     */
    public function buildSynchronizedData(
        ProductConcretePageSearchTransfer $productPageSearchTransfer,
        string $resourceName
    ): array {
        $data = [];
        $key = $this->generateResourceKey($productPageSearchTransfer, $resourceName);
        $encodedData = json_encode($productPageSearchTransfer->getData());
        $data['key'] = $key;
        $data['data'] = $encodedData;
        $data['store'] = $productPageSearchTransfer->getStore();
        $data['locale'] = $productPageSearchTransfer->getLocale();
        $data['structured_data'] = json_encode($productPageSearchTransfer->toArray());
        $data['fk_product'] = $productPageSearchTransfer->getFkProduct();

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productPageSearchTransfer
     * @param string $resourceName
     *
     * @return string
     */
    protected function generateResourceKey(
        ProductConcretePageSearchTransfer $productPageSearchTransfer,
        string $resourceName
    ) {
        $syncTransferData = new SynchronizationDataTransfer();
        $syncTransferData->setStore($productPageSearchTransfer->getStore());
        $syncTransferData->setLocale($productPageSearchTransfer->getLocale());
        $syncTransferData->setReference((string)$productPageSearchTransfer->getFkProduct());
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
                'value' => json_decode($data['data']),
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

        $importDataCollection = [];

        foreach ($this->synchronizedDataCollection as $data) {
            $importDataCollection[] = $data;

            if (count($importDataCollection) >= static::BULK_SIZE) {
                $parameter = $this->collectMultiInsertData(
                    $importDataCollection
                );
                $sql = 'INSERT INTO `spy_product_concrete_page_search` (`fk_product`, `store`, `locale`, `data`, `structured_data`, `key`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE `fk_product`=values(`fk_product`), `store`=values(`store`), `locale`=values(`locale`), `data`=values(`data`), `structured_data`=values(`structured_data`), `key`=values(`key`);';

                $connection = Propel::getConnection();
                $statement = $connection->prepare($sql);
                $statement->execute();

                $importDataCollection = [];
            }
        }
    }
}
