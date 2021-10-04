<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Business;

use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Pyz\Zed\ProductAttributeStorage\Business\ProductManagementAttributeVisibilityPublisher\ProductManagementAttributeVisibilityPublisher;
use Pyz\Zed\ProductAttributeStorage\Business\ProductManagementAttributeVisibilityPublisher\ProductManagementAttributeVisibilityPublisherInterface;
use Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageRepositoryInterface getRepository()
 * @method \Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageConfig getConfig()
 */
class ProductAttributeStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductAttributeStorage\Business\ProductManagementAttributeVisibilityPublisher\ProductManagementAttributeVisibilityPublisherInterface
     */
    public function createProductManagementAttributeVisibilityPublisher(): ProductManagementAttributeVisibilityPublisherInterface
    {
        return new ProductManagementAttributeVisibilityPublisher(
            $this->getProductAttributeFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected function getProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeStorageDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }
}
