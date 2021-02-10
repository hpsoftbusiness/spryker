<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Controller;

use Exception;
use Spryker\Zed\ProductManagement\Communication\Controller\IndexController as SprykerIndexController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function softRemoveAction(Request $request): RedirectResponse
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
        } catch (Exception $e) {
            $this->addErrorMessage($e->getMessage());
        }

        return $this->redirectResponse(self::ROUTE_INDEX);
    }
}
