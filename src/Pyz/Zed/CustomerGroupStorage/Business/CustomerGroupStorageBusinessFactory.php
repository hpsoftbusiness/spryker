<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Business;

use Pyz\Zed\CustomerGroupStorage\Business\CustomerGroup\CustomerGroupReader;
use Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageRepositoryInterface getRepository()
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageConfig getConfig()
 */
class CustomerGroupStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\CustomerGroupStorage\Business\CustomerGroup\CustomerGroupReader
     */
    public function createCustomerGroupReader(): CustomerGroupReader
    {
        return new CustomerGroupReader(
            $this->getRepository(),
            $this->getProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListFacadeInterface
     */
    public function getProductListFacade(): ProductListFacadeInterface
    {
        return $this->getProvidedDependency(CustomerGroupStorageDependencyProvider::FACADE_PRODUCT_LIST);
    }
}
