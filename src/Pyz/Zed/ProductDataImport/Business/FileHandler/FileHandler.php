<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Business\FileHandler;

use Generated\Shared\Transfer\FileSystemContentTransfer;
use Generated\Shared\Transfer\FileSystemStreamTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImport;
use Pyz\Zed\ProductDataImport\ProductDataImportConfig;
use Spryker\Service\FileSystem\FileSystemServiceInterface;
use Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface;
use Spryker\Shared\Log\LoggerTrait;
use Throwable;

class FileHandler
{
    use LoggerTrait;

    /**
     * @var \Spryker\Service\FileSystem\FileSystemServiceInterface
     */
    private $fileSystemService;

    /**
     * @param \Spryker\Service\FileSystem\FileSystemServiceInterface $fileSystemService
     */
    public function __construct(FileSystemServiceInterface $fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $flysystemConfigTransfer
     *
     * @return void
     */
    public function prepareImportFile(
        ProductDataImportTransfer $productDataImportTransfer,
        FlysystemConfigTransfer $flysystemConfigTransfer
    ): void {
        try {
            $sprykerAdapterClass = $flysystemConfigTransfer->getAdapterConfig()['sprykerAdapterClass'];
            $flysystemFilesystemBuilderPlugin = new $sprykerAdapterClass();
            $this->writeFileStream(
                $flysystemFilesystemBuilderPlugin,
                $flysystemConfigTransfer,
                $productDataImportTransfer->getFilePath()
            );
        } catch (Throwable $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }

    /**
     * @return void
     */
    public function clearImportFile(): void
    {
        try {
            $fileSystemContentTransfer = $this->createFileSystemContentTransfer();
            $this->fileSystemService->put($fileSystemContentTransfer);
        } catch (Throwable $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }

    /**
     * @param string|null $content
     *
     * @return \Generated\Shared\Transfer\FileSystemContentTransfer
     */
    private function createFileSystemContentTransfer(?string $content = null): FileSystemContentTransfer
    {
        $fileSystemContentTransfer = new FileSystemContentTransfer();

        $fileSystemContentTransfer->setPath(ProductDataImport::PRODUCT_IMPORT_FILE_NAME);
        $fileSystemContentTransfer->setContent($content);
        $fileSystemContentTransfer->setFileSystemName(ProductDataImportConfig::IMPORT_FILE_SYSTEM_NAME);

        return $fileSystemContentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FileSystemStreamTransfer
     */
    private function createFileSystemStreamTransfer(): FileSystemStreamTransfer
    {
        $fileSystemStreamTransfer = new FileSystemStreamTransfer();
        $fileSystemStreamTransfer->setPath(ProductDataImport::PRODUCT_IMPORT_FILE_NAME);
        $fileSystemStreamTransfer->setFileSystemName(ProductDataImportConfig::IMPORT_FILE_SYSTEM_NAME);

        return $fileSystemStreamTransfer;
    }

    /**
     * @param \Spryker\Service\Flysystem\Dependency\Plugin\FlysystemFilesystemBuilderPluginInterface $flysystemFilesystemBuilderPlugin
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $flysystemConfigTransfer
     * @param string $fileName
     *
     * @return void
     */
    private function writeFileStream(
        FlysystemFilesystemBuilderPluginInterface $flysystemFilesystemBuilderPlugin,
        FlysystemConfigTransfer $flysystemConfigTransfer,
        string $fileName
    ): void {
        $fileSystemContentTransfer = $this->createFileSystemStreamTransfer();
        $stream = $flysystemFilesystemBuilderPlugin->build($flysystemConfigTransfer)->readStream(
            $fileName
        );

        $this->fileSystemService->putStream($fileSystemContentTransfer, $stream);

        fclose($stream);
    }
}
