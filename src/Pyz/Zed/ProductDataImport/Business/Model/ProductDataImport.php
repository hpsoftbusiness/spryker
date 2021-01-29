<?php

namespace Pyz\Zed\ProductDataImport\Business\Model;

use Exception;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface;
use Pyz\Zed\ProductDataImport\ProductDataImportConfig;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ProductDataImport implements ProductDataImportInterface
{
    use TransactionTrait;

    /**
     * @var ProductDataImportQueryContainerInterface
     */
    private $queryContainer;
    /**
     * @var ProductDataImportConfig
     */
    private $config;

    /**
     * ProductDataImport constructor.
     * @param ProductDataImportQueryContainerInterface $queryContainer
     * @param ProductDataImportConfig $config
     */
    public function __construct(
        ProductDataImportQueryContainerInterface $queryContainer,
        ProductDataImportConfig $config
    ) {
        $this->queryContainer = $queryContainer;
        $this->config = $config;
    }

    /**
     * @param ProductDataImportTransfer $transfer
     * @return ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer): ProductDataImportTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($transfer) {
                return $this->executeAddTransaction($transfer);
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function saveFile(
        ProductDataImportTransfer $transfer,
        ProductDataImportFormDataProvider $dataProvider,
        FileSystemServiceInterface $fileSystemService
    ): void {
        $fileName = sprintf(
            '%d-%s',
            time(),
            $transfer->getFileUpload()->getClientOriginalName()
        );

        $dataImportFormDataProvider = $dataProvider->createFileSystemContentTransfer(
            $transfer->getFileUpload(),
            $fileName,
            $this->getConfig()->getStorageName()
        );
        $fileSystemService->put($dataImportFormDataProvider);

        $transfer->setFilePath(
            $this->config->getImportFileDirectory($dataImportFormDataProvider->getFileSystemName()).$fileName
        );
        $transfer->setStatus(ProductDataImportInterface::STATUS_NEW);

        $this->add($transfer);
    }

    /**
     * {@inheritDoc}
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        DataImportFacadeInterface $importFacade,
        string $dataEntity
    ): ?DataImporterReportTransfer {
        $dataImporterReportTransfer = null;
        $spyProductDataImport = $this->markInProgressByDataEntity($productDataImportTransfer, $dataEntity);
        try {
            $dataImportConfigurationActionTransfer = $this->createDataImportConfigurationActionTransfer(
                $dataEntity,
                $spyProductDataImport->getFilePath()
            );

            $dataImporterReportTransfer = $importFacade->importByAction($dataImportConfigurationActionTransfer);

            if ($dataImporterReportTransfer->getIsSuccess()) {
                $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_SUCCESS);
            } else {
                $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_FAILED);
            }
            $spyProductDataImport->save();
        } catch (Exception $exception) {
            $spyProductDataImport->setResult($exception->getMessage());
            $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_FAILED);
            $spyProductDataImport->save();
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param ProductDataImportTransfer $productDataImportTransfer
     *
     * @return ProductDataImportTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function executeAddTransaction(ProductDataImportTransfer $productDataImportTransfer
    ): ProductDataImportTransfer {
        $productDataImportEntity = new SpyProductDataImport();
        $productDataImportEntity->fromArray($productDataImportTransfer->toArray());
        $productDataImportEntity->save();

        return $productDataImportTransfer;
    }

    /**
     * @return ProductDataImportConfig
     */
    public function getConfig(): ProductDataImportConfig
    {
        return $this->config;
    }

    /**
     * @return ProductDataImportTransfer|null
     */
    public function getProductDataImportForImport(): ?ProductDataImportTransfer
    {
        $productDataImport = null;
        $spyProductDataImport = $this->queryContainer->queryProductImports()->findOneByStatus(
            ProductDataImportInterface::STATUS_NEW
        );
        if ($spyProductDataImport) {
            $productDataImport = new ProductDataImportTransfer();
            $productDataImport->fromArray($spyProductDataImport->toArray());
        }

        return $productDataImport;
    }

    /**
     * @param ProductDataImportTransfer $productDataImportTransfer
     * @param string $dataEntity
     *
     * @return SpyProductDataImport
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function markInProgressByDataEntity(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): SpyProductDataImport {
        $productDataImportEntity = $this->queryContainer->queryProductImports()->findOneByIdProductDataImport(
            $productDataImportTransfer->getIdProductDataImport()
        );
        $productDataImportEntity->setStatus(ProductDataImportInterface::STATUS_IN_PROGRESSES[$dataEntity]);
        $productDataImportEntity->save();

        return $productDataImportEntity;
    }

    /**
     * @param string $dataEntity
     * @param string $dataSource
     * @return DataImportConfigurationActionTransfer
     */
    protected function createDataImportConfigurationActionTransfer(
        string $dataEntity,
        string $dataSource
    ): DataImportConfigurationActionTransfer {
        $dataImportConfigurationActionTransfer = new DataImportConfigurationActionTransfer();
        $dataImportConfigurationActionTransfer->setDataEntity($dataEntity);
        $dataImportConfigurationActionTransfer->setSource($dataSource);

        return $dataImportConfigurationActionTransfer;
    }

    /**
     * @param string $stringResult
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function saveResult(string $stringResult, int $id): void
    {
        $productDataImportEntity = $this->queryContainer->queryProductImports()->findOneByIdProductDataImport($id);
        $productDataImportEntity->setResult($stringResult);

        $productDataImportEntity->save();
    }

    /**
     * @param int $id
     *
     * @return ProductDataImportTransfer|null
     */
    public function getProductDataImportTransferById(int $id): ?ProductDataImportTransfer
    {
        $productDataImport = null;
        $productDataImportEntity = $this->queryContainer->queryProductImports()->findOneByIdProductDataImport($id);
        if ($productDataImportEntity) {
            $productDataImport = new ProductDataImportTransfer();
            $productDataImport->fromArray($productDataImportEntity->toArray());
        }

        return $productDataImport;
    }
}
