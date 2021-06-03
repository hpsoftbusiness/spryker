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
 * @SuppressWarnings(PHPMD.FacadeRule)
 *
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportBusinessFactory getFactory()
 */
class ProductDataImportFacade extends AbstractFacade implements ProductDataImportFacadeInterface
{
    /**
     * @inheritDoc
     */
    public function saveFile(ProductDataImportTransfer $transfer, ProductDataImportFormDataProvider $dataProvider): void
    {
        $fileSystemService = $this->getFactory()->getFileSystem();
        $this->getFactory()->createProductDataImport()->saveFile($transfer, $dataProvider, $fileSystemService);
    }

    /**
     * @inheritDoc
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
            $dataImporterReportTransfer->setImportType($dataEntity),
            $productDataImportTransfer->getIdProductDataImport()
        );
    }

    /**
     * @inheritDoc
     */
    public function saveImportResult(DataImporterReportTransfer $dataImporterReportTransfer, int $id): void
    {
        $productDataImportResultTransfer =
            $this->getFactory()
                ->createProductDataImportResult()
                ->getDataImporterReportResultTransfers($dataImporterReportTransfer);

        $this->getFactory()->createProductDataImport()->saveResult($productDataImportResultTransfer, $id);
    }

    /**
     * @inheritDoc
     */
    public function getProductDataImportTransferById(int $id): ?ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->getProductDataImportTransferById($id);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function clearImportFile(): void
    {
        $this->getFactory()->createFileHandler()->clearImportFile();
    }

    /**
     * @inheritDoc
     */
    public function setMainStatus(int $productDataImportId): void
    {
        $this->getFactory()->createProductDataImport()->setMainStatus($productDataImportId);
    }
}
