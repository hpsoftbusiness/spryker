<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategoryStorage\Business;

use Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageFacade as SprykerProductCategoryStorageFacade;

/**
 * @method \Pyz\Zed\ProductCategoryStorage\Business\ProductCategoryStorageBusinessFactory getFactory()
 */
class ProductCategoryStorageFacade extends SprykerProductCategoryStorageFacade implements ProductCategoryStorageFacadeInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $this->getFactory()->createProductCategoryStorageWriter()->publish($productAbstractIds);
    }
}
