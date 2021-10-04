<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Persistence;

use Orm\Zed\ProductAttributeStorage\Persistence\PyzProductManagementAttributeVisibilityStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageRepositoryInterface getRepository()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageConfig getConfig()
 */
class ProductAttributeStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAttributeStorage\Persistence\PyzProductManagementAttributeVisibilityStorageQuery
     */
    public function createProductManagementAttributeVisibilityStorageQuery(): PyzProductManagementAttributeVisibilityStorageQuery
    {
        return PyzProductManagementAttributeVisibilityStorageQuery::create();
    }
}
