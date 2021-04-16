<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CatalogPage\ChildrenFinder;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;

interface CategoryChildrenFinderInterface
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
    ): bool;
}
