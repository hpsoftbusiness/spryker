<?php

namespace Pyz\Zed\ProductDataImport\Business\Model;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Spryker\Service\FileSystem\FileSystemServiceInterface;

interface ProductDataImportInterface
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESSES = [
        'combined-product-abstract' => 'in progress (product-abstract)',
        'combined-product-abstract-store' => 'in progress(product-abstract-store)',
        'combined-product-concrete' => 'in progress (product-concrete)',
        'combined-product-image' => 'in progress (product-image)',
        'combined-product-price' => 'in progress (product-price)',
        'combined-product-stock' => 'in progress (product-stock)',
        'combined-product-group' => 'in progress (product-group)',
    ];
    public const STATUS_SUCCESS = 'successful';
    public const STATUS_FAILED = 'failed';


    /**
     * @param ProductDataImportTransfer $transfer
     *
     * @return ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer): ProductDataImportTransfer;

    /**
     * @param ProductDataImportTransfer $transfer
     * @param ProductDataImportFormDataProvider $dataProvider
     * @param FileSystemServiceInterface $fileSystemService
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     */
    public function saveFile(
        ProductDataImportTransfer $transfer,
        ProductDataImportFormDataProvider $dataProvider,
        FileSystemServiceInterface $fileSystemService
    ): void;

    /**
     * @param ProductDataImportTransfer $productDataImportTransfer
     * @param DataImportFacadeInterface $importFacade
     * @param string $dataEntity
     *
     * @return DataImporterReportTransfer|null
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        DataImportFacadeInterface $importFacade,
        string $dataEntity
    ): ?DataImporterReportTransfer;
}
