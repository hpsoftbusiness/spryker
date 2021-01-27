<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImportInterface;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportBusinessFactory getFactory()
 */
class ProductDataImportFacade extends AbstractFacade implements ProductDataImportFacadeInterface
{
    /**
     * @param ProductDataImportTransfer $transfer
     *
     * @return ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer): ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->add($transfer);
    }

    /**
     * @param ProductDataImportTransfer $transfer
     * @param ProductDataImportFormDataProvider $dataProvider
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function saveFile(ProductDataImportTransfer $transfer, ProductDataImportFormDataProvider $dataProvider): void
    {
        $fileName = sprintf(
            '%d-%s',
            time(),
            $transfer->getFileUpload()->getClientOriginalName()
        );
        $transfer->setFilePath($fileName);
        $transfer->setStatus(ProductDataImportInterface::STATUS_NEW);

        $productDataImport = $this->getFactory()->createProductDataImport();
        $dataImportFormDataProvider = $dataProvider->createFileSystemContentTransfer(
            $transfer,
            $fileName,
            $productDataImport->getConfig()->getStorageName()
        );
        $this->getFactory()->getFileSystem()->put($dataImportFormDataProvider);

        $productDataImport->add($transfer);
    }
}
