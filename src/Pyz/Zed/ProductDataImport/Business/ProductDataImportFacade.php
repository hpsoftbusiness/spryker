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
     * {@inheritDoc}
     */
    public function saveFile(ProductDataImportTransfer $transfer, ProductDataImportFormDataProvider $dataProvider): void
    {
        $fileSystemService = $this->getFactory()->getFileSystem();
        $this->getFactory()->createProductDataImport()->saveFile($transfer, $dataProvider, $fileSystemService);
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
