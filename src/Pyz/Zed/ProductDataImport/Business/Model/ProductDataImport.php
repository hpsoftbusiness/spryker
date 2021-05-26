<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business\Model;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportMessageTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportResultTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface;
use Pyz\Zed\ProductDataImport\ProductDataImportConfig;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Throwable;

class ProductDataImport implements ProductDataImportInterface
{
    use TransactionTrait;

    public const PRODUCT_IMPORT_FILE_NAME = 'combined_product.csv';

    /**
     * @var \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var \Pyz\Zed\ProductDataImport\ProductDataImportConfig
     */
    private $config;

    /**
     * @param \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface $queryContainer
     * @param \Pyz\Zed\ProductDataImport\ProductDataImportConfig $config
     */
    public function __construct(
        ProductDataImportQueryContainerInterface $queryContainer,
        ProductDataImportConfig $config
    ) {
        $this->queryContainer = $queryContainer;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer
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
     * @inheritDoc
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

        $dataImportFormDataProvider = $dataProvider->createFileSystemStreamTransfer(
            $fileName,
            $this->getConfig()->getStorageName()
        );

        $stream = fopen($transfer->getFileUpload()->getRealPath(), "rb");
        $fileSystemService->putStream($dataImportFormDataProvider, $stream);
        fclose($stream);

        $transfer->setFilePath($fileName);

        $transfer->setStatus(ProductDataImportInterface::STATUS_NEW);

        $this->add($transfer);
    }

    /**
     * @inheritDoc
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        DataImportFacadeInterface $importFacade,
        string $dataEntity,
        string $importFileDirectory
    ): ?DataImporterReportTransfer {
        $dataImporterReportTransfer = new DataImporterReportTransfer();
        $spyProductDataImport = $this->markInProgressByDataEntity($productDataImportTransfer, $dataEntity);
        try {
            $dataImportConfigurationActionTransfer = $this->createDataImportConfigurationActionTransfer(
                $dataEntity,
                $importFileDirectory . self::PRODUCT_IMPORT_FILE_NAME
            );

            $dataImporterReportTransfer = $importFacade->importByAction($dataImportConfigurationActionTransfer);

            if ($dataImporterReportTransfer->getIsSuccess()) {
                $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_SUCCESS);
            } else {
                $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_FAILED);
            }
            $spyProductDataImport->save();
        } catch (Throwable $exception) {
            $message = (new DataImporterReportMessageTransfer())->setMessage($exception->getMessage());
            $dataImporterReportTransfer->setImportType($dataEntity);
            $dataImporterReportTransfer->setIsSuccess(false);
            $dataImporterReportTransfer->addDataImporterReport(
                (new DataImporterReportTransfer())->addMessage($message)->setIsSuccess(false)->setImportType(
                    $dataEntity
                )->setImportedDataSetCount(0)->setExpectedImportableDataSetCount(0)
            );

            $spyProductDataImport->setStatus(ProductDataImportInterface::STATUS_FAILED);

            $spyProductDataImport->save();
        }

        return $dataImporterReportTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer
     */
    protected function executeAddTransaction(ProductDataImportTransfer $productDataImportTransfer): ProductDataImportTransfer
    {
        $productDataImportEntity = new SpyProductDataImport();
        $productDataImportEntity->fromArray($productDataImportTransfer->toArray());
        $productDataImportEntity->save();

        return $productDataImportTransfer;
    }

    /**
     * @return \Pyz\Zed\ProductDataImport\ProductDataImportConfig
     */
    public function getConfig(): ProductDataImportConfig
    {
        return $this->config;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
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
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     * @param string $dataEntity
     *
     * @return \Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport
     */
    protected function markInProgressByDataEntity(
        ProductDataImportTransfer $productDataImportTransfer,
        string $dataEntity
    ): SpyProductDataImport {
        $productDataImportEntity = $this->queryContainer->queryProductImports()->findOneByIdProductDataImport(
            $productDataImportTransfer->getIdProductDataImport()
        );
        $productDataImportEntity->setStatus($this->generateInProgressStatus($dataEntity));
        $productDataImportEntity->save();

        return $productDataImportEntity;
    }

    /**
     * @param string $dataEntity
     * @param string $dataSource
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
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
     * @param \Generated\Shared\Transfer\ProductDataImportResultTransfer $productDataImportResultTransfer
     * @param int $id
     *
     * @return void
     */
    public function saveResult(ProductDataImportResultTransfer $productDataImportResultTransfer, int $id): void
    {
        $productDataImportEntity = $this->queryContainer->queryProductImports()->findOneByIdProductDataImport($id);
        $result = json_decode($productDataImportEntity->getResult(), true) ?? [];
        array_push($result, $productDataImportResultTransfer->toArrayRecursiveCamelCased());

        $productDataImportEntity->setResult(json_encode($result));

        $this->getTransactionHandler()->handleTransaction(
            function () use ($productDataImportEntity) {
                $productDataImportEntity->save();
            }
        );
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer|null
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

    /**
     * @param int $productDataImportId
     *
     * @return void
     */
    public function setMainStatus(int $productDataImportId): void
    {
        $productDataImportEntity = $this->queryContainer->queryProductImports()->findOneByIdProductDataImport(
            $productDataImportId
        );

        $statuses = array_map(
            function (array $productDataImportResultArray) {
                return $productDataImportResultArray['status'];
            },
            json_decode($productDataImportEntity->getResult(), true)
        );

        $productDataImportEntity->setStatus($this->getStatus($statuses));

        $this->getTransactionHandler()->handleTransaction(
            function () use ($productDataImportEntity) {
                $productDataImportEntity->save();
            }
        );
    }

    /**
     * @param array $statuses
     *
     * @return string
     */
    private function getStatus(array $statuses): string
    {
        $successArray = array_filter(
            $statuses,
            function ($status) {
                return $status === ProductDataImportInterface::STATUS_SUCCESS;
            }
        );
        $failedArray = array_filter(
            $statuses,
            function ($status) {
                return $status === ProductDataImportInterface::STATUS_FAILED;
            }
        );
        if (count($statuses) === count($successArray)) {
            return ProductDataImportInterface::STATUS_SUCCESS;
        }
        if (count($statuses) === count($failedArray)) {
            return ProductDataImportInterface::STATUS_FAILED;
        }

        return ProductDataImportInterface::STATUS_PARTIAL_FAILED;
    }

    /**
     * @param string $dataEntity
     *
     * @return string
     */
    private function generateInProgressStatus(string $dataEntity): string
    {
        return sprintf(ProductDataImportInterface::STATUS_IN_PROGRESS, $dataEntity);
    }
}
