<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Communication\Plugin\Synchronization;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Pyz\Shared\CustomerGroupStorage\CustomerGroupStorageConstants;
use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroupStorage\Business\CustomerGroupStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageConfig getConfig()
 */
class CustomerGroupSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataQueryContainerPluginInterface
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
        return CustomerGroupStorageConstants::CUSTOMER_GROUP_RESOURCE_NAME;
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
     * @param int[] $ids
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function queryData($ids = []): ?ModelCriteria
    {
        return $this->getQueryContainer()->queryCustomerGroupStorageByCustomerGroupIds($ids);
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
        return CustomerGroupStorageConstants::CUSTOMER_GROUP_SYNC_STORAGE_QUEUE;
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
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
