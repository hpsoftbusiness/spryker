<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacade getFacade()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class ViewController extends AbstractController
{
    public const ROUTE_PATH = '/product-data-import/view';
    public const PARAM_ID_PRODUCT_IMPORT_DATA = 'id-product-data-import';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $id = $request->get(static::PARAM_ID_PRODUCT_IMPORT_DATA);
        $productDataImport = $this->getFacade()->getProductDataImportTransferById((int)$id);

        return $this->viewResponse(
            [
                'productDataImport' => $productDataImport,
            ]
        );
    }
}
