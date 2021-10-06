<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CartPage\Plugin\Router;

use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin as SprykerRouterPlugin;
use Symfony\Component\HttpFoundation\Request;

class CartPageRouteProviderPlugin extends SprykerRouterPlugin
{
    /**
     * route name for order overview deletion needs
     */
    public const ROUTE_NAME_CART_REMOVE_WITHOUT_VALIDATION_TOKEN = 'cart/remove-without-validate-token';

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
        $routeCollection = $this->addRemoveWithoutValidationTokenRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveWithoutValidationTokenRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/remove-without-validate-token/{sku}/{groupKey}', 'CartPage', 'Cart', 'removeWithoutValidateTokenAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setDefault('groupKey', '');
        $route = $route->setMethods(Request::METHOD_POST);

        $routeCollection->add(static::ROUTE_NAME_CART_REMOVE_WITHOUT_VALIDATION_TOKEN, $route);

        return $routeCollection;
    }
}
