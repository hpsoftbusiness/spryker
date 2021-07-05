<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;

interface TransferMapperInterface
{
    /**
     * @param array $productEntityCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $title
     *
     * @return \Generated\Shared\Transfer\ProductsResponseApiTransfer
     */
    public function toTransferCollection(
        array $productEntityCollection,
        LocaleTransfer $localeTransfer,
        string $title
    ): ProductsResponseApiTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrlTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $productCategoryTransferCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        ProductUrlTransfer $productUrlTransfer,
        LocaleTransfer $localeTransfer,
        CategoryCollectionTransfer $productCategoryTransferCollection
    ): ProductApiTransfer;
}
