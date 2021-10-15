<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;
use Pyz\Client\Weclapp\WeclappClientInterface;
use Pyz\Shared\Weclapp\WeclappConfig;
use Pyz\Zed\Category\Business\CategoryFacadeInterface;

class CategoryExporter implements CategoryExporterInterface
{
    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Pyz\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Pyz\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        CategoryFacadeInterface $categoryFacade
    ) {
        $this->weclappClient = $weclappClient;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @return void
     */
    public function exportAllCategories(): void
    {
        $rootCategoryCollectionTransfer = $this->getRootCategoryCollectionWithChildren();

        foreach ($rootCategoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $this->exportCategoryWithChildren($categoryTransfer, null);
        }
    }

    /**
     * @param array $categoriesIds
     *
     * @return void
     */
    public function exportCategories(array $categoriesIds): void
    {
        $categoryCollectionTransfer = $this->getCategoryCollectionByIds($categoriesIds);
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $this->exportCategoryWithParents($categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return void
     */
    protected function exportCategoryWithChildren(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): void {
        $parentWeclappArticleCategoryTransfer = $this->exportCategory($categoryTransfer, $parentWeclappArticleCategoryTransfer);
        foreach ($categoryTransfer->getCategoryNode()->getChildrenNodes()->getNodes() as $node) {
            $categoryTransfer = $node->getCategory();
            $categoryTransfer->setCategoryNode($node);

            $this->exportCategoryWithChildren($categoryTransfer, $parentWeclappArticleCategoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    protected function exportCategoryWithParents(CategoryTransfer $categoryTransfer): WeclappArticleCategoryTransfer
    {
        $parentWeclappArticleCategoryTransfer = null;
        $parentCategoryTransfer = $this->getParentCategory($categoryTransfer);
        if ($parentCategoryTransfer) {
            $parentWeclappArticleCategoryTransfer = $this->weclappClient
                ->getArticleCategory($parentCategoryTransfer);
            if (!$parentWeclappArticleCategoryTransfer) {
                $parentWeclappArticleCategoryTransfer = $this->exportCategoryWithParents($parentCategoryTransfer);
            }
        }

        return $this->exportCategory($categoryTransfer, $parentWeclappArticleCategoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    protected function exportCategory(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): WeclappArticleCategoryTransfer {
        $weclappArticleCategoryTransfer = $this->weclappClient->getArticleCategory($categoryTransfer);

        if ($weclappArticleCategoryTransfer) {
            $weclappArticleCategoryTransfer = $this->weclappClient->putArticleCategory(
                $categoryTransfer,
                $weclappArticleCategoryTransfer,
                $parentWeclappArticleCategoryTransfer
            );
        } else {
            $weclappArticleCategoryTransfer = $this->weclappClient->postArticleCategory(
                $categoryTransfer,
                $parentWeclappArticleCategoryTransfer
            );
        }
        $this->saveIdWeclappArticleCategory($categoryTransfer, $weclappArticleCategoryTransfer);

        return $weclappArticleCategoryTransfer;
    }

    /**
     * @param array $categoriesIds
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    protected function getCategoryCollectionByIds(array $categoriesIds): CategoryCollectionTransfer
    {
        $categoryCriteriaTransfer = new CategoryCriteriaTransfer();
        $categoryCriteriaTransfer->setIdCategories($categoriesIds)
            ->setLocaleName(WeclappConfig::LOCALE_CODE);

        return $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    protected function getRootCategoryCollectionWithChildren(): CategoryCollectionTransfer
    {
        $categoryCriteriaTransfer = new CategoryCriteriaTransfer();
        $categoryCriteriaTransfer->setLocaleName(WeclappConfig::LOCALE_CODE)
            ->setWithChildrenRecursively(true)
            ->setIsRoot(true);

        return $this->categoryFacade->getCategoryCollection($categoryCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    protected function getParentCategory(CategoryTransfer $categoryTransfer): ?CategoryTransfer
    {
        if (!$categoryTransfer->getCategoryNode()->getFkParentCategoryNode()) {
            return null;
        }

        $categoryCriteriaTransfer = new CategoryCriteriaTransfer();
        $categoryCriteriaTransfer->setIdCategoryNode($categoryTransfer->getCategoryNode()->getFkParentCategoryNode())
            ->setLocaleName(WeclappConfig::LOCALE_CODE);

        return $this->categoryFacade->findCategory($categoryCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer
     *
     * @return void
     */
    protected function saveIdWeclappArticleCategory(
        CategoryTransfer $categoryTransfer,
        WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer
    ): void {
        $categoryTransferClone = clone $categoryTransfer;
        $categoryTransferClone->setCategoryNode(null); // value is not needed for update and causes segmentation fault
        $categoryTransferClone->setIdWeclappArticleCategory($weclappArticleCategoryTransfer->getIdOrFail());

        $this->categoryFacade->updateCategoryEntity($categoryTransferClone);
    }
}
