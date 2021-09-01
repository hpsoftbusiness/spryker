<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttributeStorage\Storage;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Pyz\Client\ProductAttributeStorage\Mapper\ProductAttributeStorageMapperInterface;
use Pyz\Shared\ProductAttributeStorage\ProductAttributeStorageConstants;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;

class ProductAttributeStorageReader implements ProductAttributeStorageReaderInterface
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
     * @var \Pyz\Client\ProductAttributeStorage\Mapper\ProductAttributeStorageMapperInterface
     */
    protected $productAttributeStorageMapper;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Service\Synchronization\SynchronizationServiceInterface $synchronizationService
     * @param \Pyz\Client\ProductAttributeStorage\Mapper\ProductAttributeStorageMapperInterface $productAttributeStorageMapper
     */
    public function __construct(
        StorageClientInterface $storageClient,
        SynchronizationServiceInterface $synchronizationService,
        ProductAttributeStorageMapperInterface $productAttributeStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->productAttributeStorageMapper = $productAttributeStorageMapper;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(): ProductAttributeKeysCollectionTransfer
    {
        $productAttributeKeys = $this->storageClient->get($this->generateStorageKey());

        if ($productAttributeKeys === null) {
            $productAttributeKeys = [];
        }

        return $this->productAttributeStorageMapper
            ->mapArrayToProductAttributeKeysCollectionTransfer($productAttributeKeys);
    }

    /**
     * @return string
     */
    protected function generateStorageKey(): string
    {
        return $this->synchronizationService
            ->getStorageKeyBuilder(ProductAttributeStorageConstants::PRODUCT_MANAGEMENT_ATTRIBUTE_VISIBILITY_RESOURCE_NAME)
            ->generateKey(new SynchronizationDataTransfer());
    }
}
