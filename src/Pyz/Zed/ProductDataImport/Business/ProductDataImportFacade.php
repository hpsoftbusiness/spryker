<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\DataImporterReportTransfer;
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
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     * @param string $dataEntity
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer|null
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): ?DataImporterReportTransfer {
        $dataImport = $this->getFactory()->getDataImport();
        $importFileDirectory = $this->getFactory()->getImportFileDirectory();

        return $this->getFactory()->createProductDataImport()->import(
            $productDataImportTransfer,
            $dataImport,
            $dataEntity,
            $importFileDirectory
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
        $stringResult = $this->getFactory()->createProductDataImportResult(
        )->collectionDataImporterReportTransferToString($resultArray);

        $this->getFactory()->createProductDataImport()->saveResult($stringResult, $id);
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
     * @param ProductDataImportTransfer $productDataImportTransfer
     */
    public function prepareImportFile(ProductDataImportTransfer $productDataImportTransfer): void
    {
        $flysystemConfigTransfer = $this->getFactory()->createFlysystemConfigTransfer();
        $this->getFactory()->createFileHandler()->prepareImportFile($productDataImportTransfer, $flysystemConfigTransfer);
    }

    public function clearImportFile(): void
    {
        $this->getFactory()->createFileHandler()->clearImportFile();
    }
}
