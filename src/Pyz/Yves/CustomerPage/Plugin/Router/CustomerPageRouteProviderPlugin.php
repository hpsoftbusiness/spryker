<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Router;

use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin as SprykerShopCustomerPageRouteProviderPlugin;

class CustomerPageRouteProviderPlugin extends SprykerShopCustomerPageRouteProviderPlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addLoginRoute($routeCollection);
        $routeCollection = $this->addLogoutRoute($routeCollection);
        $routeCollection = $this->addForgottenPasswordRoute($routeCollection);
        $routeCollection = $this->addRestorePasswordRoute($routeCollection);
        $routeCollection = $this->addCustomerOverviewRoute($routeCollection);
        $routeCollection = $this->addCustomerProfileRoute($routeCollection);
        $routeCollection = $this->addCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addNewCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addUpdateCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addDeleteCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addRefreshCustomerAddressRoute($routeCollection);
        $routeCollection = $this->addCustomerOrderRoute($routeCollection);
        $routeCollection = $this->addCustomerOrderDetailsRoute($routeCollection);
        $routeCollection = $this->addCustomerDeleteRoute($routeCollection);
        $routeCollection = $this->addCustomerDeleteConfirmRoute($routeCollection);
        $routeCollection = $this->addAccessTokenRoute($routeCollection);

        return $routeCollection;
    }
}
