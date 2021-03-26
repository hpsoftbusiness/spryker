<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportBusinessFactory getFactory()
 */
class ProductDataImportFacade extends AbstractFacade implements ProductDataImportFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $transfer
     * @param \Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider $dataProvider
     *
     * @return void
     */
    public function saveFile(ProductDataImportTransfer $transfer, ProductDataImportFormDataProvider $dataProvider): void
    {
        $fileSystemService = $this->getFactory()->getFileSystem();
        $this->getFactory()->createProductDataImport()->saveFile($transfer, $dataProvider, $fileSystemService);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
     */
    public function getProductDataImportForImport(): ?ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->getProductDataImportForImport();
    }

    /**
     * @inheritDoc
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): void {
        $dataImport = $this->getFactory()->getDataImport();
        $importFileDirectory = $this->getFactory()->getImportFileDirectory();

        $dataImporterReportTransfer = $this->getFactory()->createProductDataImport()->import(
            $productDataImportTransfer,
            $dataImport,
            $dataEntity,
            $importFileDirectory
        );

        $this->saveImportResult(
            [$dataImporterReportTransfer->setImportType($dataEntity)],
            $productDataImportTransfer->getIdProductDataImport()
        );
    }

    /**
     * @param array $resultArray
     * @param int $id
     *
     * @return void
     */
    public function saveImportResult(array $resultArray, int $id): void
    {
        $resultArray = $this->getFactory()->createProductDataImportResult(
        )->collectionDataImporterReportTransferToString($resultArray);

        $this->getFactory()->createProductDataImport()->saveResult($resultArray, $id);
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
     */
    public function getProductDataImportTransferById(int $id): ?ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->getProductDataImportTransferById($id);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     *
     * @return void
     */
    public function prepareImportFile(ProductDataImportTransfer $productDataImportTransfer): void
    {
        $flysystemConfigTransfer = $this->getFactory()->createFlysystemConfigTransfer();
        $this->getFactory()->createFileHandler()->prepareImportFile(
            $productDataImportTransfer,
            $flysystemConfigTransfer
        );
    }

    /**
     * @return void
     */
    public function clearImportFile(): void
    {
        $this->getFactory()->createFileHandler()->clearImportFile();
    }
}
