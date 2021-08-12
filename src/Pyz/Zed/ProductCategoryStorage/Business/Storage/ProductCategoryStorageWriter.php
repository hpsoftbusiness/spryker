<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategoryStorage\Business\Storage;

use Spryker\Zed\ProductCategoryStorage\Business\Storage\ProductCategoryStorageWriter as SprykerProductCategoryStorageWriter;

class ProductCategoryStorageWriter extends SprykerProductCategoryStorageWriter
{
    /**
     * @var \Pyz\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractCategories(array $productAbstractIds)
    {
        $productCategories = $this->queryContainer->queryAllProductCategoryWithCategoryNodes($productAbstractIds)->find();
        $productCategoryMappings = [];
        foreach ($productCategories as $mapping) {
            $productCategoryMappings[$mapping->getFkProductAbstract()][] = $mapping;
        }

        return $productCategoryMappings;
    }
}
