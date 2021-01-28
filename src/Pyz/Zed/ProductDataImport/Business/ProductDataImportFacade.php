<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\DataProvider\ProductDataImportFormDataProvider;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportBusinessFactory getFactory()
 */
class ProductDataImportFacade extends AbstractFacade implements ProductDataImportFacadeInterface
{
    /**
     * {@inheritDoc}
     */
    public function saveFile(ProductDataImportTransfer $transfer, ProductDataImportFormDataProvider $dataProvider): void
    {
        $fileSystemService = $this->getFactory()->getFileSystem();
        $this->getFactory()->createProductDataImport()->saveFile($transfer, $dataProvider, $fileSystemService);
    }
}
