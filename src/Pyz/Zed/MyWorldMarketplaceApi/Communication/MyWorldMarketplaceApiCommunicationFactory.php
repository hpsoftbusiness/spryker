<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Communication;

use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Client\Sso\SsoClientInterface;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiDependencyProvider;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Calculation\Business\CalculationFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Refund\Business\RefundFacadeInterface;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiFacadeInterface getFacade()
 */
class MyWorldMarketplaceApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\CalculationFacadeInterface
     */
    public function getCalculationFacade(): CalculationFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\Refund\Business\RefundFacadeInterface
     */
    public function getRefundFacade(): RefundFacadeInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    public function getMyWorldMarketplaceApiClient(): MyWorldMarketplaceApiClientInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::CLIENT_MY_WORLD_MARKETPLACE_API);
    }

    /**
     * @return \Pyz\Client\Sso\SsoClientInterface
     */
    public function getSsoClient(): SsoClientInterface
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::SSO_CLIENT);
    }
}
