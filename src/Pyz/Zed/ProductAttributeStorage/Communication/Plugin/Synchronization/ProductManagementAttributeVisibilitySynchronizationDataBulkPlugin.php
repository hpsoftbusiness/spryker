<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\ProductAttributeStorage\Persistence\Map\PyzProductManagementAttributeVisibilityStorageTableMap;
use Pyz\Shared\ProductAttributeStorage\ProductAttributeStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Business\ProductAttributeStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductAttributeStorage\Communication\ProductAttributeStorageCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageConfig getConfig()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageRepositoryInterface getRepository()
 */
class ProductManagementAttributeVisibilitySynchronizationDataBulkPlugin extends AbstractPlugin implements SynchronizationDataBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductAttributeStorageConstants::PRODUCT_MANAGEMENT_ATTRIBUTE_VISIBILITY_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductAttributeStorageConstants::PRODUCT_ATTRIBUTE_SYNC_STORAGE_QUEUE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getSynchronizationPoolName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(int $offset, int $limit, array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        $customerAccessStorageEntityTransfers = $this->getRepository()
            ->findFilteredProductManagementAttributeVisibilityStorageEntities($filterTransfer, $ids);

        foreach ($customerAccessStorageEntityTransfers as $customerAccessStorageEntityTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $data = $customerAccessStorageEntityTransfer->getData();
            $synchronizationDataTransfer->setData($data);
            $synchronizationDataTransfer->setKey($customerAccessStorageEntityTransfer->getKey());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOrderBy(PyzProductManagementAttributeVisibilityStorageTableMap::COL_ID_PRODUCT_MANAGEMENT_ATTRIBUTE_VISIBILITY_STORAGE)
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
