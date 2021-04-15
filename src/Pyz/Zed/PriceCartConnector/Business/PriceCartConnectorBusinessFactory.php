<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceCartConnector\Business;

use Pyz\Zed\PriceCartConnector\Business\BenefitDeals\BenefitPriceManager;
use Pyz\Zed\PriceCartConnector\Business\BenefitDeals\BenefitPriceManagerInterface;
use Pyz\Zed\PriceCartConnector\PriceCartConnectorDependencyProvider;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory as SprykerPriceCartConnectorBusinessFactory;

class PriceCartConnectorBusinessFactory extends SprykerPriceCartConnectorBusinessFactory
{
    /**
     * @return \Pyz\Zed\PriceCartConnector\Business\BenefitDeals\BenefitPriceManagerInterface
     */
    public function createBenefitPriceManager(): BenefitPriceManagerInterface
    {
        return new BenefitPriceManager($this->getPriceProductFacade());
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface|\Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(PriceCartConnectorDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
