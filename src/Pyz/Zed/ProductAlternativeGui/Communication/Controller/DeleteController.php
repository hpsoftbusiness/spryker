<?php

namespace Pyz\Zed\ProductAlternativeGui\Communication\Controller;

use Spryker\Zed\ProductAlternativeGui\Communication\Controller\DeleteController as SprykerDeleteController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteController extends SprykerDeleteController
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $this->redirectResponse($request->headers->get('referer') . static::KEY_TAB_PRODUCT_ALTERNATIVE);
    }
}
