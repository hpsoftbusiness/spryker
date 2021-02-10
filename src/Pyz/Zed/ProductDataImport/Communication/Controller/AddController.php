<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Controller;

use Exception;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacade getFacade()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{
    public const ROUTE_PATH = '/product-data-import/add';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createProductDataImportFormDataProvider();

        $form = $this->getFactory()->createProductDataImportForm(
            $dataProvider->getData(),
            $dataProvider->getOptions()
        )->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    /** @var \Generated\Shared\Transfer\ProductDataImportTransfer $productDataImportTransfer */
                    $productDataImportTransfer = $form->getData();

                    $this->getFacade()->saveFile($productDataImportTransfer, $dataProvider);

                    $this->addSuccessMessage('The file was added successfully.');

                    return $this->redirectResponse(IndexController::ROUTE_PATH);
                } catch (Exception $exception) {
                    $this->addErrorMessage($exception->getMessage());
                }
            } else {
                $this->addErrorMessage((string)$form->getErrors(true, false));
            }
        }

        return $this->viewResponse(
            [
                'form' => $form->createView(),
            ]
        );
    }
}
