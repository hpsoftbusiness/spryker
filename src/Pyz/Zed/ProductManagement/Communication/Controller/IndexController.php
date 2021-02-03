<?php

namespace Pyz\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\ProductManagement\Communication\Controller\IndexController as SprykerIndexController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class IndexController extends SprykerIndexController
{
    public const ROUTE_INDEX = '/product-management';
    public const ROUTE_SOFT_REMOVE = '/product-management/index/soft-remove';

    private const SUCCESS_REMOVE_MESSAGE = 'Product deleted';

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function softRemoveAction(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $idProductAbstract = $this->castId(
            $request->get(
                self::ID_PRODUCT_ABSTRACT
            )
        );
        try {
            /** @var \Pyz\Zed\Product\Business\ProductFacade $productFacade */
            $productFacade = $this->getFactory()->getProductFacade();
            $productFacade->markProductAsRemoved($idProductAbstract);
            $this->addSuccessMessage(self::SUCCESS_REMOVE_MESSAGE);
        } catch (\Exception $e) {
            $this->addErrorMessage($e->getMessage());
        }

       return $this->redirectResponse(self::ROUTE_INDEX);
    }
}
