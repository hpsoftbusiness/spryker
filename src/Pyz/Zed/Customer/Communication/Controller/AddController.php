<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Controller;

use Spryker\Zed\Customer\Communication\Controller\AddController as SprykerAddController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AddController extends SprykerAddController
{
    /**
     * @uses \Spryker\Zed\Customer\Communication\Controller\IndexController::indexAction()
     */
    protected const ROUTE_CUSTOMER_TABLE = '/customer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        return $this->redirectResponse(static::ROUTE_CUSTOMER_TABLE);
    }
}
