<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategory\Communication;

use Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory as SprykerProductCategoryCommunicationFactory;
use Spryker\Zed\ProductCategory\Dependency\QueryContainer\ProductCategoryToCategoryInterface;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;

class ProductCategoryCommunicationFactory extends SprykerProductCategoryCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategory\Dependency\QueryContainer\ProductCategoryToCategoryInterface
     */
    public function getCategoryQueryContainer(): ProductCategoryToCategoryInterface
    {
        return $this->getProvidedDependency(ProductCategoryDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }
}
