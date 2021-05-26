<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business\Model;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\DataImport\Business\DataImportFacadeInterface;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Spryker\Service\FileSystem\FileSystemServiceInterface;

interface ProductDataImportInterface
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in progress (%s)';
    public const STATUS_SUCCESS = 'successful';
    public const STATUS_FAILED = 'failed';
    public const STATUS_PARTIAL_FAILED = 'partial failed';

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer): ProductDataImportTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $transfer
     * @param \Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider $dataProvider
     * @param \Spryker\Service\FileSystem\FileSystemServiceInterface $fileSystemService
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function saveFile(
        ProductDataImportTransfer $transfer,
        ProductDataImportFormDataProvider $dataProvider,
        FileSystemServiceInterface $fileSystemService
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     * @param \Pyz\Zed\DataImport\Business\DataImportFacadeInterface $importFacade
     * @param string $dataEntity
     * @param string $importFileDirectory
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer|null
     */
    public function import(
        ProductDataImportTransfer $productDataImportTransfer,
        DataImportFacadeInterface $importFacade,
        string $dataEntity,
        string $importFileDirectory
    ): ?DataImporterReportTransfer;
}
