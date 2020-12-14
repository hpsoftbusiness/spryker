<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepository as SprykerProductCategoryRepository;

class ProductCategoryRepository extends SprykerProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstractWithoutLocale(
        int $idProductAbstract
    ): CategoryCollectionTransfer {
        $categoryEntities = $this->getFactory()->createProductCategoryQuery()
            ->innerJoinWithSpyCategory()
            ->filterByFkProductAbstract($idProductAbstract)
            ->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($categoryEntities, new CategoryCollectionTransfer());
    }
}
