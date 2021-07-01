<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Calculation\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CalculationRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NAME_RECALCULATE = 'recalculate';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addRecalculateRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRecalculateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/calculation/recalculate',
            'Calculation',
            'Calculation',
            'recalculateAction'
        );
        $routeCollection->add(static::ROUTE_NAME_RECALCULATE, $route);

        return $routeCollection;
    }
}
