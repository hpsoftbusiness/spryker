<?php

namespace Pyz\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;

interface TransferMapperInterface
{
    /**
     * @param array $productEntityCollection
     * @param LocaleTransfer $localeTransfer,
     * @param string $title
     *
     * @return ProductsResponseApiTransfer
     */
    public function toTransferCollection(
        array $productEntityCollection,
        LocaleTransfer $localeTransfer,
        string $title
    ): ProductsResponseApiTransfer;

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     * @param ProductUrlTransfer $productUrlTransfer
     * @param LocaleTransfer $localeTransfer
     * @param CategoryCollectionTransfer $productCategoryTransferCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        ProductUrlTransfer $productUrlTransfer,
        LocaleTransfer $localeTransfer,
        CategoryCollectionTransfer $productCategoryTransferCollection
    );
}
