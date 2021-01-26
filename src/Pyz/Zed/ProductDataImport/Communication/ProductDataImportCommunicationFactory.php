<?php

namespace Pyz\Zed\ProductDataImport\Communication;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Pyz\Zed\ProductDataImport\Communication\Form\ProductDataImportForm;
use Pyz\Zed\ProductDataImport\Communication\Table\ProductDataImportTable;
use Pyz\Zed\ProductDataImport\ProductDataImportDependencyProvider;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 */
class ProductDataImportCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return ProductDataImportTable
     */
    public function createProductImportTable(): ProductDataImportTable
    {
        return new ProductDataImportTable($this->getQueryContainer());
    }

    /**
     * @return ProductDataImportFormDataProvider
     */
    public function createProductDataImportFormDataProvider(): ProductDataImportFormDataProvider
    {
        return new ProductDataImportFormDataProvider($this->getQueryContainer());
    }

    /**
     * @param ProductDataImportTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductDataImportForm(
        ProductDataImportTransfer $data,
        array $options = []
    ): \Symfony\Component\Form\FormInterface {
        return $this->getFormFactory()->create(ProductDataImportForm::class, $data, $options);
    }

    /**
     * @return FileSystemServiceInterface
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileSystem(): FileSystemServiceInterface
    {
        return $this->getProvidedDependency(ProductDataImportDependencyProvider::FILE_SYSTEM_SERVICE);
    }

    /**
     * @param ProductDataImportTransfer $transfer
     * @param string $filePath
     *
     * @return FileSystemContentTransfer
     */
    public function getFileSystemContentTransfer(
        ProductDataImportTransfer $transfer,
        string $filePath
    ): FileSystemContentTransfer {
        $fileContent = file_get_contents($transfer->getFileUpload()->getRealPath());

        $fileSystemContentTransfer = new FileSystemContentTransfer();
        $fileSystemContentTransfer->setContent($fileContent);
        $fileSystemContentTransfer->setFileSystemName($this->getConfig()->getStorageName());
        $fileSystemContentTransfer->setPath($filePath);

        return $fileSystemContentTransfer;
    }
}
