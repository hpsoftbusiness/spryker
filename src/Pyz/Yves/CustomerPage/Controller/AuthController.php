<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Controller;

use SprykerShop\Yves\CustomerPage\Controller\AuthController as SprykerAuthController;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class AuthController extends SprykerAuthController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction()
    {
        if (!$this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            return parent::loginAction();
        }

        if (!$this->isLoggedInCustomer()) {
            $referer = null;
            $hasReferer = $this->getRequestStack()->getCurrentRequest()->headers->has('referer');
            if ($hasReferer) {
                $referer = $this->getRequestStack()->getCurrentRequest()->headers->get('referer');
            }

            return $this->redirectResponseExternal($this->getFactory()->getSsoClient()->getAuthorizeUrl($this->getLocale(), $referer));
        }

        $redirectUrl = $this->getRedirectUrlFromPlugins();
        if ($redirectUrl) {
            return $this->redirectResponseExternal($redirectUrl);
        }

        return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_NAME_CUSTOMER_OVERVIEW);
    }

    /**
     * @return mixed[]
     */
    protected function executeLoginAction(): array
    {
        if ($this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            return [];
        }

        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getLoginForm();

        return [
            'loginForm' => $loginForm->createView(),
            'registerForm' => null,
        ];
    }
}
