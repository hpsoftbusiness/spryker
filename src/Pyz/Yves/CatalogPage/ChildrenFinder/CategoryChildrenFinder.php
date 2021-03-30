<?php

namespace Pyz\Yves\CatalogPage\ChildrenFinder;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;

class CategoryChildrenFinder implements CategoryChildrenFinderInterface
{
    /**
     * @param CategoryNodeStorageTransfer $categoryNode
     * @param FacetSearchResultTransfer $filter
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
            return (bool)sizeof($categoryNode->getChildren());
        }

        foreach ($categoryNode->getChildren() as $child) {
            if ($this->existsCategoryProduct($child, $filter)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param CategoryNodeStorageTransfer $categoryNode
     * @param FacetSearchResultTransfer $filter
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
