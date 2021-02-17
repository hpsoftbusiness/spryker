<?php

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
     * @var FileSystemServiceInterface
     */
    private $fileSystemService;

    /**
     * FileHandler constructor.
     * @param FileSystemServiceInterface $fileSystemService
     */
    public function __construct(FileSystemServiceInterface $fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }

    /**
     * @param ProductDataImportTransfer $productDataImportTransfer
     * @param FlysystemConfigTransfer $flysystemConfigTransfer
     */
    public function prepareImportFile(
        ProductDataImportTransfer $productDataImportTransfer,
        FlysystemConfigTransfer $flysystemConfigTransfer
    ): void {
        try {
            $sprykerAdapterClass = $flysystemConfigTransfer->getAdapterConfig()['sprykerAdapterClass'];
            $flysystemFilesystemBuilderPlugin = new $sprykerAdapterClass;
            $this->writeFileStream(
                $flysystemFilesystemBuilderPlugin,
                $flysystemConfigTransfer,
                $productDataImportTransfer->getFilePath()
            );
        } catch (Throwable $exception) {
            $this->getLogger()->error($exception->getMessage());
        }
    }

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
     * @return FileSystemContentTransfer
     */
    private function createFileSystemContentTransfer(string $content = null): FileSystemContentTransfer
    {
        $fileSystemContentTransfer = new FileSystemContentTransfer();

        $fileSystemContentTransfer->setPath(ProductDataImport::PRODUCT_IMPORT_FILE_NAME);
        $fileSystemContentTransfer->setContent($content);
        $fileSystemContentTransfer->setFileSystemName(ProductDataImportConfig::IMPORT_FILE_SYSTEM_NAME);

        return $fileSystemContentTransfer;
    }

    /**
     * @return FileSystemStreamTransfer
     */
    private function createFileSystemStreamTransfer(): FileSystemStreamTransfer
    {
        $fileSystemStreamTransfer = new FileSystemStreamTransfer();
        $fileSystemStreamTransfer->setPath(ProductDataImport::PRODUCT_IMPORT_FILE_NAME);
        $fileSystemStreamTransfer->setFileSystemName(ProductDataImportConfig::IMPORT_FILE_SYSTEM_NAME);

        return $fileSystemStreamTransfer;
    }

    /**
     * @param FlysystemFilesystemBuilderPluginInterface $flysystemFilesystemBuilderPlugin
     * @param FlysystemConfigTransfer $flysystemConfigTransfer
     * @param string $fileName
     *
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
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
