<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Controller;

use SprykerShop\Yves\CheckoutPage\Controller\CheckoutController as SprykerShopCheckoutController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageFactory getFactory()
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function benefitVoucherAction(Request $request)
    {
        /** @var \Pyz\Yves\CheckoutPage\Form\FormFactory $formFactory */
        $formFactory = $this->getFactory()->createCheckoutFormFactory();
        $response = $this->createStepProcess()->process(
            $request,
            $formFactory->getBenefitFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/benefit-deal/benefit-deal.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return mixed
     */
    public function errorAction(?Request $request = null)
    {
        if ($request === null) {
            return $this->view([], [], '@CheckoutPage/views/order-fail/order-fail.twig');
        }

        $response = $this->createStepProcess()->process($request);

        if (!is_array($response)) {
            return $response;
        }

        $errorMessages = array_unique($this->getFactory()->getMessengerClient()->getFlashErrorMessages());
        $response = array_merge($response, ['errors' => $errorMessages]);

        return $this->view($response, [], '@CheckoutPage/views/order-fail/order-fail.twig');
    }
}
