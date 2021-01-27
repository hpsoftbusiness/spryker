<?php

namespace Pyz\Zed\ProductDataImport\Communication\Controller;

use Pyz\Zed\ProductDataImport\Business\Model\ProductDataImportInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Exception;

/**
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacade getFacade()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainer getQueryContainer()
 */
class AddController extends AbstractController
{
    public const ROUTE_PATH = '/product-data-import/add';

    /**
     * @param Request $request
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
                    $fileName = sprintf(
                        '%d-%s',
                        time(),
                        $productDataImportTransfer->getFileUpload()->getClientOriginalName()
                    );
                    $productDataImportTransfer->setFilePath($fileName);
                    $productDataImportTransfer->setStatus(ProductDataImportInterface::STATUS_NEW);

                    $dataImportFormDataProvider = $this->getFactory()->getFileSystemContentTransfer(
                        $productDataImportTransfer,
                        $fileName
                    );
                    $this->getFactory()->getFileSystem()->put($dataImportFormDataProvider);

                    $this->getFacade()->add($productDataImportTransfer);

                    $this->addSuccessMessage('The file was added successfully.');

                    return $this->redirectResponse(IndexController::ROUTE_PATH);
                } catch (Exception $exception) {
                    $this->addErrorMessage($exception->getMessage());
                }
            }
            $this->addErrorMessage($form->getErrors()->current()->getMessage());
        }

        return $this->viewResponse(
            [
                'form' => $form->createView(),
            ]
        );
    }
}
