<?php

namespace Pyz\Zed\ProductDataImport\Communication\Form\DataProvider;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface;

class ProductDataImportFormDataProvider
{
    /**
     * @var ProductDataImportQueryContainerInterface
     */
    private $queryContainer;

    /**
     * ProductDataImportFormDataProvider constructor.
     * @param ProductDataImportQueryContainerInterface $queryContainer
     */
    public function __construct(ProductDataImportQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int|null $idProductDataImport
     *
     * @return ProductDataImportTransfer
     */
    public function getData(int $idProductDataImport = null): ProductDataImportTransfer
    {
        if ($idProductDataImport === null) {
            return new ProductDataImportTransfer();
        }

        $productDataImportEntity = $this->queryContainer->queryProductDataImportById($idProductDataImport)->findOne();

        if (!$productDataImportEntity) {
            return new ProductDataImportTransfer();
        }

        return (new ProductDataImportTransfer())->fromArray($productDataImportEntity->toArray(), true);

    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * @param ProductDataImportTransfer $transfer
     * @param string $filePath
     * @param string $storageName
     * @return FileSystemContentTransfer
     */
    public function createFileSystemContentTransfer(
        ProductDataImportTransfer $transfer,
        string $filePath,
        string $storageName
    ): FileSystemContentTransfer {
        $fileContent = file_get_contents($transfer->getFileUpload()->getRealPath());

        $fileSystemContentTransfer = new FileSystemContentTransfer();
        $fileSystemContentTransfer->setContent($fileContent);
        $fileSystemContentTransfer->setFileSystemName($storageName);
        $fileSystemContentTransfer->setPath($filePath);

        return $fileSystemContentTransfer;
    }
}
