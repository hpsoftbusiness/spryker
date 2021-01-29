<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;

interface ProductDataImportFacadeInterface
{
    /**
     * @param ProductDataImportTransfer $transfer
     * @param ProductDataImportFormDataProvider $dataProvider
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function saveFile(
        ProductDataImportTransfer $transfer,
        ProductDataImportFormDataProvider $dataProvider
    ): void;
}
