<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use SprykerShop\Yves\CustomerPage\Controller\ProfileController as SprykerProfileController;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends SprykerProfileController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_OVERVIEW);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getLoggedInCustomerTransfer()
    {
        return parent::getLoggedInCustomerTransfer() ?? new CustomerTransfer();
    }
}
