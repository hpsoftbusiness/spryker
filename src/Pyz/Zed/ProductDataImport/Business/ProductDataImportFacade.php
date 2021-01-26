<?php

namespace Pyz\Zed\ProductDataImport\Business;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportBusinessFactory getFactory()
 */
class ProductDataImportFacade extends AbstractFacade implements ProductDataImportFacadeInterface
{
    /**
     * @param ProductDataImportTransfer $transfer
     *
     * @return ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer) :ProductDataImportTransfer
    {
        return $this->getFactory()->createProductDataImport()->add($transfer);
    }
}
