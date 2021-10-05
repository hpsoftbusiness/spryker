<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategoryStorage\Business;

use Pyz\Zed\ProductCategoryStorage\Business\Storage\ProductCategoryStorageWriter;
use Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageBusinessFactory as SprykerProductCategoryStorageBusinessFactory;

/**
 * @method \Pyz\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface getQueryContainer()
 */
class ProductCategoryStorageBusinessFactory extends SprykerProductCategoryStorageBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductCategoryStorage\Business\Storage\ProductCategoryStorageWriter
     */
    public function createProductCategoryStorageWriter(): ProductCategoryStorageWriter
    {
        return new ProductCategoryStorageWriter(
            $this->getCategoryFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }
}
