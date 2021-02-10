<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;

interface ProductDataImportFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $transfer
     * @param \Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider $dataProvider
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    public function saveFile(
        ProductDataImportTransfer $transfer,
        ProductDataImportFormDataProvider $dataProvider
    ): void;

    /**
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
     */
    public function getProductDataImportForImport(): ?ProductDataImportTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     * @param string $dataEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer|null
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): ?DataImporterReportTransfer;

    /**
     * @param array $resultArray
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function saveImportResult(array $resultArray, int $id): void;

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
     */
    public function getProductDataImportTransferById(int $id): ?ProductDataImportTransfer;
}
