<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Communication\Plugin\Event;

use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Pyz\Shared\CustomerGroupStorage\CustomerGroupStorageConstants;
use Pyz\Zed\CustomerGroup\Dependency\CustomerGroupEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroupStorage\Business\CustomerGroupStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageConfig getConfig()
 */
class CustomerGroupEventResourceQueryContainerPlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
     * @param int[] $ids
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function queryData(array $ids = []): ?ModelCriteria
    {
        return $this->getQueryContainer()->queryCustomerGroupByIds($ids);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return CustomerGroupEvents::CUSTOMER_GROUP_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyCustomerGroupTableMap::COL_ID_CUSTOMER_GROUP;
    }
}
