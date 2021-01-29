<?php

namespace Pyz\Zed\ProductDataImport\Business\Model;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Spryker\Service\FileSystem\FileSystemServiceInterface;

interface ProductDataImportInterface
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in progress';
    public const STATUS_SUCCESS = 'successful';
    public const STATUS_FAILED = 'failed';

    public const ALL_STATUSES = [
        self::STATUS_NEW,
        self::STATUS_IN_PROGRESS,
        self::STATUS_SUCCESS,
        self::STATUS_FAILED,
    ];

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
}
