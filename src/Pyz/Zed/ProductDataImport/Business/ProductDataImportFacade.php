<?php

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
     * {@inheritDoc}
     */
    public function saveFile(ProductDataImportTransfer $transfer, ProductDataImportFormDataProvider $dataProvider): void
    {
        $fileSystemService = $this->getFactory()->getFileSystem();
        $this->getFactory()->createProductDataImport()->saveFile($transfer, $dataProvider, $fileSystemService);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductDataImportForImport(): ?ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->getProductDataImportForImport();
    }

    /**
     * {@inheritDoc}
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): ?DataImporterReportTransfer {
        $dataImport = $this->getFactory()->getDataImport();

        return $this->getFactory()->createProductDataImport()->import(
            $productDataImportTransfer,
            $dataImport,
            $dataEntity
        );
    }

    /**
     * {@inheritDoc}
     */
    public function saveImportResult(array $resultArray, int $id): void
    {
        $stringResult = $this->getFactory()->createProductDataImportResult(
        )->collectionDataImporterReportTransferToString($resultArray);

        $this->getFactory()->createProductDataImport()->saveResult($stringResult, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function getProductDataImportTransferById(int $id): ?ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->getProductDataImportTransferById($id);
    }
}
