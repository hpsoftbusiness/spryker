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
     * @return void
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): void;

    /**
     * @param \Generated\Shared\Transfer\DataImporterReportTransfer $dataImporterReportTransfer
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function saveImportResult(DataImporterReportTransfer $dataImporterReportTransfer, int $id): void;

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     *
     * @return void
     */
    public function prepareImportFile(ProductDataImportTransfer $productDataImportTransfer): void;

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
     */
    public function getProductDataImportTransferById(int $id): ?ProductDataImportTransfer;

    /**
     * @return void
     */
    public function clearImportFile(): void;

    /**
     * @param int $productDataImportId
     *
     * @return void
     */
    public function setMainStatus(int $productDataImportId): void;
}
