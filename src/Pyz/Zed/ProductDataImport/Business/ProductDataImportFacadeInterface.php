<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\ProductDataImportTransfer;

interface ProductDataImportFacadeInterface
{
    /**
     * @param ProductDataImportTransfer $transfer
     *
     * @return ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer): ProductDataImportTransfer;
}
