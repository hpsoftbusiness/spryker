<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImport;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImportInterface;
use Pyz\Zed\ProductDataImport\ProductDataImportDependencyProvider;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Service\Flysystem\FlysystemServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class ProductDataImportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return ProductDataImport
     */
    public function createProductDataImport(): ProductDataImport
    {
        return new ProductDataImport($this->getQueryContainer(), $this->getConfig());
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
     * @return DataImportFacadeInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getDataImport(): DataImportFacadeInterface
    {
        return $this->getProvidedDependency(ProductDataImportDependencyProvider::DATA_IMPORT_FACADE);
    }

    /**
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport|null
     */
    public function getProductDataImportForImport(): ?\Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport
    {
        return $this->getQueryContainer()->queryProductImports()->findOneByStatus(
            ProductDataImportInterface::STATUS_NEW
        );
    }
}
