<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAlternativeGui\Communication\Controller;

use Spryker\Zed\ProductAlternativeGui\Communication\Controller\DeleteController as SprykerDeleteController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteController extends SprykerDeleteController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $this->redirectResponse($request->headers->get('referer') . static::KEY_TAB_PRODUCT_ALTERNATIVE);
    }
}
