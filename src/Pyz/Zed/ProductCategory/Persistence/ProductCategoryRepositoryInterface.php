<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface as SprykerProductCategoryRepositoryInterface;

interface ProductCategoryRepositoryInterface extends SprykerProductCategoryRepositoryInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstractWithoutLocale(
        int $idProductAbstract
    ): CategoryCollectionTransfer;
}
