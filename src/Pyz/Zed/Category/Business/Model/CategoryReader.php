<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Spryker\Zed\Category\Business\Model\CategoryReader as SprykerCategoryReader;

/**
 * @property \Pyz\Zed\Category\Persistence\CategoryRepositoryInterface $repository
 */
class CategoryReader extends SprykerCategoryReader implements CategoryReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryCollection(
        CategoryCriteriaTransfer $categoryCriteriaTransfer
    ): CategoryCollectionTransfer {
        $categoryCollection = $this->repository->getCategoryCollectionByCriteria($categoryCriteriaTransfer);

        foreach ($categoryCollection->getCategories() as $categoryTransfer) {
            if ($categoryCriteriaTransfer->getWithChildren()
                || $categoryCriteriaTransfer->getWithChildrenRecursively()
            ) {
                $categoryNodeCollectionTransfer = $this->categoryTreeReader->getCategoryNodeCollectionTree(
                    $categoryTransfer,
                    $categoryCriteriaTransfer
                );

                $categoryTransfer->setNodeCollection($categoryNodeCollectionTransfer);
            }

            $this->categoryPluginExecutor->executePostReadPlugins($categoryTransfer);
        }

        return $categoryCollection;
    }
}
