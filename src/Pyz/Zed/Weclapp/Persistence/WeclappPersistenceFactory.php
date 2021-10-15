<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Persistence;

use Pyz\Zed\Product\Persistence\ProductQueryContainerInterface;
use Pyz\Zed\Weclapp\WeclappDependencyProvider;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface getRepository()
 * @method \Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface getEntityManager()
 */
class WeclappPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    public function getCustomerQueryContainer(): CustomerQueryContainerInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @return \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductQueryContainerInterface
    {
        return $this->getProvidedDependency(WeclappDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
