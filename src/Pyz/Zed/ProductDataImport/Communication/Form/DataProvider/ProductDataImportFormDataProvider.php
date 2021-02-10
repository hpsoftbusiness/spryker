<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Form\DataProvider;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface;

class ProductDataImportFormDataProvider
{
    /**
     * @var \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface $queryContainer
     */
    public function __construct(ProductDataImportQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int|null $idProductDataImport
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer
     */
    public function getData(?int $idProductDataImport = null): ProductDataImportTransfer
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
     * @param \Generated\Shared\Transfer\FileUploadTransfer $transfer
     * @param string $filePath
     * @param string $storageName
     *
     * @return \Generated\Shared\Transfer\FileSystemContentTransfer
     */
    public function createFileSystemContentTransfer(
        FileUploadTransfer $transfer,
        string $filePath,
        string $storageName
    ): FileSystemContentTransfer {
        $fileContent = file_get_contents($transfer->getRealPath());

        $fileSystemContentTransfer = new FileSystemContentTransfer();
        $fileSystemContentTransfer->setContent($fileContent);
        $fileSystemContentTransfer->setFileSystemName($storageName);
        $fileSystemContentTransfer->setPath($filePath);

        return $fileSystemContentTransfer;
    }
}
