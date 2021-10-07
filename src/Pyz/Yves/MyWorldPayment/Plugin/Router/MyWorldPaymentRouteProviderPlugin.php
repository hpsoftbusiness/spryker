<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MyWorldPaymentRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NAME_MY_WORLD_PAYMENT = 'myWorldPayment';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addSendCodeCustomerBySmsRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSendCodeCustomerBySmsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute(
            '/my-world-payment/send-code-customer-by-sms',
            'MyWorldPayment',
            'MyWorldPayment',
            'sendSmsCodeToCustomerAction'
        );
        $routeCollection->add(static::ROUTE_NAME_MY_WORLD_PAYMENT, $route);

        return $routeCollection;
    }
}
