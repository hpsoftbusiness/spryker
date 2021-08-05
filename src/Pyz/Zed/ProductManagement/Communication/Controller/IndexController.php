<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Communication\Controller;

use Exception;
use Spryker\Zed\ProductManagement\Communication\Controller\IndexController as SprykerIndexController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    protected const RESPONSE_SUCCESS_KEY = 'success';
    protected const RESPONSE_MESSAGE = 'message';

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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function groupFunctionAction(Request $request): JsonResponse
    {
        $form = $this->getFactory()
            ->createTableGroupActionForm()
            ->handleRequest($request);
        $groupActionTransfer = $this->getFactory()
            ->createProductFormTransferGenerator()
            ->buildGroupActionTransfer($form, $request->get('ids'));
        if (!$groupActionTransfer->getIds() || !$groupActionTransfer->getAction()) {
            return $this->jsonResponse([
                static::RESPONSE_SUCCESS_KEY => false,
                static::RESPONSE_MESSAGE => 'You have to choose products and action',
            ]);
        }
        $this->getFacade()->groupAction($groupActionTransfer);

        return $this->jsonResponse([
            static::RESPONSE_SUCCESS_KEY => true,
            static::RESPONSE_MESSAGE => 'Products have been updated',
        ]);
    }
}
