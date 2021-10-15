<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Category\Persistence\CategoryRepository as SprykerCategoryRepository;

/**
 * @method \Pyz\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryRepository extends SprykerCategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryCollectionByCriteria(
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): CategoryCollectionTransfer {
        $categoryQuery = $this->getFactory()->createCategoryQuery();
        $categoryQuery = $this->applyCategoryFilters($categoryQuery, $categoryCriteriaTransfer);
        $categoryQuery->leftJoinWithAttribute();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollectionWithRelations($categoryQuery->find());
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $categoryQuery
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function applyCategoryFilters(
        SpyCategoryQuery $categoryQuery,
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): SpyCategoryQuery {
        $categoryQuery = parent::applyCategoryFilters($categoryQuery, $categoryCriteriaTransfer);

        if ($categoryCriteriaTransfer->getIdCategories()) {
            $categoryQuery->filterByIdCategory_In($categoryCriteriaTransfer->getIdCategories());
        }

        if ($categoryCriteriaTransfer->getIsRoot()) {
            $categoryQuery->useNodeQuery()
                ->filterByIsRoot(true)
                ->endUse();
        }

        if ($categoryCriteriaTransfer->getIdCategoryNode()) {
            $categoryQuery->useNodeQuery()
                ->filterByIdCategoryNode($categoryCriteriaTransfer->getIdCategoryNode())
                ->endUse();
        }

        return $categoryQuery;
    }
}
