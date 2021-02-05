<?php

namespace Pyz\Zed\ProductDataImport\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacade getFacade()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{
    public const ROUTE_PATH = '/product-data-import';

    /**
     * @return array
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()->createProductImportTable();

        return $this->viewResponse(
            [
                'productImportTable' => $table->render(),
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $table = $this->getFactory()->createProductImportTable();

        return $this->jsonResponse($table->fetchData());
    }
}
