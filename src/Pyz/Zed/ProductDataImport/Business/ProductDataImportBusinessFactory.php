<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\ProductDataImport\Business\DataProvider\ProductDataImportResult;
use Pyz\Zed\ProductDataImport\Business\FileHandler\FileHandler;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImport;
use Pyz\Zed\ProductDataImport\ProductDataImportDependencyProvider;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class ProductDataImportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductDataImport\Business\Model\ProductDataImport
     */
    public function createProductDataImport(): ProductDataImport
    {
        return new ProductDataImport($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    public function getFileSystem(): FileSystemServiceInterface
    {
        return $this->getProvidedDependency(ProductDataImportDependencyProvider::FILE_SYSTEM_SERVICE);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\DataImportFacadeInterface
     */
    public function getDataImport(): DataImportFacadeInterface
    {
        return $this->getProvidedDependency(ProductDataImportDependencyProvider::DATA_IMPORT_FACADE);
    }

    /**
     * @return \Pyz\Zed\ProductDataImport\Business\DataProvider\ProductDataImportResult
     */
    public function createProductDataImportResult(): ProductDataImportResult
    {
        return new ProductDataImportResult();
    }

    /**
     * @return \Pyz\Zed\ProductDataImport\Business\FileHandler\FileHandler
     */
    public function createFileHandler(): FileHandler
    {
        return new FileHandler($this->getFileSystem());
    }
    
    /**
     * @return string
     */
    public function getImportFileDirectory(): string
    {
        return $this->getConfig()->getImportFileDirectory();
    }

    /**
     * @return \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    public function createFlysystemConfigTransfer(): FlysystemConfigTransfer
    {
        $flysystemConfigTransfer = new FlysystemConfigTransfer();
        $flysystemConfigTransfer->setAdapterConfig($this->getConfig()->getFlyAdapterConfig());
        $flysystemConfigTransfer->setName($this->getConfig()->getStorageName());

        return $flysystemConfigTransfer;
    }
}
