<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Exception;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
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
        $productDataImport = $this->getFactory()->createProductDataImport();

        $dataImportFormDataProvider = $dataProvider->createFileSystemContentTransfer(
            $transfer->getFileUpload(),
            $fileName,
            $productDataImport->getConfig()->getStorageName()
        );
        $fileImportDirectory = $productDataImport->getConfig()->getImportFileDirectory(
            $dataImportFormDataProvider->getFileSystemName()
        );

        $transfer->setStatus(ProductDataImportInterface::STATUS_NEW);
        $transfer->setFilePath($fileImportDirectory.$fileName);

        $this->getFactory()->getFileSystem()->put($dataImportFormDataProvider);

        $productDataImport->add($transfer);
    }

    /**
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport|null
     */
    public function getProductDataImportForImport(): ?\Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport
    {
        return $this->getFactory()->getProductDataImportForImport();
    }

    /**
     * @param string $dataEntity
     * @param string $dataSource
     *
     * @return DataImporterReportTransfer
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function importFile(string $dataEntity, string $dataSource): DataImporterReportTransfer
    {
        $dataImportConfigurationActionTransfer = new DataImportConfigurationActionTransfer();
        $dataImportConfigurationActionTransfer->setDataEntity($dataEntity);
        $dataImportConfigurationActionTransfer->setSource($dataSource);

        return $this->getFactory()->getDataImport()->importByAction($dataImportConfigurationActionTransfer);
    }

    /**
     * @param SpyProductDataImport $spyProductDataImport
     * @param string $dataEntity
     *
     * @return string|null
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function import(SpyProductDataImport $spyProductDataImport, string $dataEntity): ?string
    {
        try {
            $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_IN_PROGRESS);
            $spyProductDataImport->save();

            $dataImporterReportTransfer = $this->importFile($dataEntity, $spyProductDataImport->getFilePath());

            if ($dataImporterReportTransfer->getIsSuccess()) {
                $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_SUCCESS);
            } else {
                $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_FAILED);
            }
            $result = json_encode($dataImporterReportTransfer->modifiedToArray());
            $spyProductDataImport->setResult($result);

            $spyProductDataImport->save();
        } catch (Exception $exception) {
            $spyProductDataImport->setResult($exception->getMessage());
            $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_FAILED);

            $spyProductDataImport->save();
        }

        return $spyProductDataImport->getResult();
    }
}
