<?php

namespace Pyz\Zed\ProductDataImport;

use Pyz\Shared\ProductDataImport\ProductDataImportConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductDataImportConfig extends AbstractBundleConfig
{
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
}
