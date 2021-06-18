<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Business\ProductAbstractAttributeBusinessFactory getFactory()
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface getRepository()
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface getEntityManager()
 */
class ProductAbstractAttributeFacade extends AbstractFacade implements ProductAbstractAttributeFacadeInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function saveProductAbstractAttributes(array $productAbstractIds): void
    {
        $this->getFactory()->createProductAbstractAttributePropelWriter()->save($productAbstractIds);
    }
}
