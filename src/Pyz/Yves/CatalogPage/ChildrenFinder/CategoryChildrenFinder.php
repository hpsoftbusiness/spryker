<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CatalogPage\ChildrenFinder;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;

class CategoryChildrenFinder implements CategoryChildrenFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNode
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $filter
     * @param bool $isEmptyCategoryFilterValueVisible
     *
     * @return bool
     */
    public function existsCategoryChild(
        CategoryNodeStorageTransfer $categoryNode,
        FacetSearchResultTransfer $filter,
        bool $isEmptyCategoryFilterValueVisible
    ): bool {
        if ($isEmptyCategoryFilterValueVisible) {
            return (bool)count($categoryNode->getChildren());
        }

        foreach ($categoryNode->getChildren() as $child) {
            if ($this->existsCategoryProduct($child, $filter)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNode
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $filter
     *
     * @return bool
     */
    protected function existsCategoryProduct(
        CategoryNodeStorageTransfer $categoryNode,
        FacetSearchResultTransfer $filter
    ): bool {
        foreach ($filter->getValues() as $filterValue) {
            if ($filterValue->getValue() == $categoryNode->getNodeId()) {
                return (bool)$filterValue->getDocCount();
            }
        }

        return false;
    }
}
