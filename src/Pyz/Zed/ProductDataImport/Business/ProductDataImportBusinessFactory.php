<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImport;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class ProductDataImportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return ProductDataImport
     */
    public function createProductDataImport(): ProductDataImport
    {
        return new ProductDataImport($this->getQueryContainer());
    }
}
