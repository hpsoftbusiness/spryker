<?php

namespace Pyz\Zed\MyWorldMarketplaceApi\Communication;

use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiDependencyProvider;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Calculation\Business\CalculationFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiQueryContainer getQueryContainer()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
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
}
