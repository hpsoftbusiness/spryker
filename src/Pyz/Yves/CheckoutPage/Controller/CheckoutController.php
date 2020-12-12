<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Controller;

use SprykerShop\Yves\CheckoutPage\Controller\CheckoutController as SprykerShopCheckoutController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 * @method \Spryker\Client\Checkout\CheckoutClientInterface getClient()
 */
class CheckoutController extends SprykerShopCheckoutController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adyen3dSecureAction(Request $request)
    {
        $response = $this->createStepProcess()->process($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/adyen-3d-secure/adyen-3d-secure.twig'
        );
    }
}
