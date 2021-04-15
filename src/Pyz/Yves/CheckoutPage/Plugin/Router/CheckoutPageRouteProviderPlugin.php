<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Plugin\Router;

use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin as SprykerShopCheckoutPageRouteProviderPlugin;

class CheckoutPageRouteProviderPlugin extends SprykerShopCheckoutPageRouteProviderPlugin
{
    public const ROUTE_NAME_CHECKOUT_ADYEN_3D_SECURE = 'checkout-adyen-3d-secure';

    public const ROUTE_NAME_CHECKOUT_BENEFIT = 'checkout-my-world-payment-benefit';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = parent::addRoutes($routeCollection);
        $routeCollection = $this->addAdyen3dSecureRoute($routeCollection);
        $routeCollection = $this->addBenefitVoucherRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAdyen3dSecureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/adyen-3d-secure', 'CheckoutPage', 'Checkout', 'adyen3dSecureAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_ADYEN_3D_SECURE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addBenefitVoucherRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/checkout/benefit', 'CheckoutPage', 'Checkout', 'benefitVoucherAction');
        $route = $route->setMethods(['GET', 'POST']);
        $routeCollection->add(static::ROUTE_NAME_CHECKOUT_BENEFIT, $route);

        return $routeCollection;
    }
}
