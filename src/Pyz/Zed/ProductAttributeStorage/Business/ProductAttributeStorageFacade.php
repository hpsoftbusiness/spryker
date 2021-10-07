<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Business\ProductAttributeStorageBusinessFactory getFactory()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageRepositoryInterface getRepository()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface getEntityManager()
 */
class ProductAttributeStorageFacade extends AbstractFacade implements ProductAttributeStorageFacadeInterface
{
    /**
     * @return void
     */
    public function publishProductManagementAttributeVisibility(): void
    {
        $this->getFactory()->createProductManagementAttributeVisibilityPublisher()->publish();
    }
}
