<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Business\CategoryFacade as SprykerCategoryFacade;

/**
 * @method \Pyz\Zed\Category\Business\CategoryBusinessFactory getFactory()
 */
class CategoryFacade extends SprykerCategoryFacade implements CategoryFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryCollection(
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): CategoryCollectionTransfer {
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoryCollection($categoryCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryEntity(CategoryTransfer $categoryTransfer): void
    {
        $this->getFactory()
            ->createCategoryCategory()
            ->update($categoryTransfer);
    }
}
