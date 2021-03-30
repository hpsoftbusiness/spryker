<?php

namespace Pyz\Yves\CatalogPage\ChildrenFinder;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;

interface CategoryChildrenFinderInterface
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
    ): bool;
}
