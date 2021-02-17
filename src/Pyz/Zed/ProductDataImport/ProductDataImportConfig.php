<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport;

use Pyz\Shared\ProductDataImport\ProductDataImportConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductDataImportConfig extends AbstractBundleConfig
{
    public const IMPORT_FILE_SYSTEM_NAME = 'product-import';

    /**
     * @return string
     */
    public function getDefaultFileMaxSize(): string
    {
        return $this->get(ProductDataImportConstants::DEFAULT_FILE_MAX_SIZE);
    }

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return $this->get(ProductDataImportConstants::STORAGE_NAME);
    }

    /**
     * @return string
     */
    public function getImportFileDirectory(): string
    {
        $filesystemConfig = $this->get(ProductDataImportConstants::FILESYSTEM_SERVICE, []);

        $rootPath = $filesystemConfig[self::IMPORT_FILE_SYSTEM_NAME]['root'];
        $folderPath = $filesystemConfig[self::IMPORT_FILE_SYSTEM_NAME]['path'];

        return $rootPath.$folderPath;
    }

    /**
     * @return array
     */
    public function getFlyAdapterConfig(): array
    {
        return $this->get(ProductDataImportConstants::FILESYSTEM_SERVICE, [])[$this->get(
            ProductDataImportConstants::STORAGE_NAME
        )];
    }
}
