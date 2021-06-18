<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CustomerGroupStorage\Storage;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Pyz\Shared\CustomerGroupStorage\CustomerGroupStorageConstants;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;

class CustomerGroupStorageReader
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected static $storageKeyBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        StorageClientInterface $storageClient,
        SynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idCustomerGroup
     *
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getCustomerProductListCollectionByIdCustomerGroup(int $idCustomerGroup): CustomerProductListCollectionTransfer
    {
        $data = $this
            ->storageClient
            ->get(
                $this->generateKey($idCustomerGroup)
            );
        if (!$data) {
            $data = [];
        }

        $collection = new CustomerProductListCollectionTransfer();
        $customerGroupTransfer = (new CustomerGroupTransfer())->fromArray($data, true);
        foreach ($customerGroupTransfer->getProductLists() as $productList) {
            if ($productList->getProductList()->getType() === SpyProductListTableMap::COL_TYPE_WHITELIST) {
                $collection->addWhitelistId(
                    $productList->getIdProductList()
                );
            } elseif ($productList->getProductList()->getType() === SpyProductListTableMap::COL_TYPE_BLACKLIST) {
                $collection->addBlacklistId(
                    $productList->getIdProductList()
                );
            }
        }

        return $collection;
    }

    /**
     * @param int $idCustomerGroup
     *
     * @return string
     */
    protected function generateKey(int $idCustomerGroup): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference((string)$idCustomerGroup);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(CustomerGroupStorageConstants::CUSTOMER_GROUP_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }
}
