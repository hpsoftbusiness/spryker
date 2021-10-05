<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CartPage\Controller;

use SprykerShop\Yves\CartPage\Controller\CartController as SprykerCartController;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartController extends SprykerCartController
{
    public const REQUEST_HEADER_REFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $sku)
    {
        parent::addAction($request, $sku);

        return $this->redirect($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request, $sku)
    {
        parent::removeAction($request, $sku);

        return $this->redirect($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeWithoutValidateTokenAction(Request $request, $sku)
    {
        $groupKey = $request->get('groupKey', null);

        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $this->getFactory()
            ->getCartClient()
            ->removeItem($sku, $groupKey);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->jsonResponse([]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirect(Request $request)
    {
        if ($request->headers->has(static::REQUEST_HEADER_REFERER)) {
            return $this->redirectResponseExternal($request->headers->get(static::REQUEST_HEADER_REFERER));
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
