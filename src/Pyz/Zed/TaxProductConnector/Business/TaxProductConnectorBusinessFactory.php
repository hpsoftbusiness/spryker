<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\TaxProductConnector\Business;

use Pyz\Zed\TaxProductConnector\Business\Calculator\ProductItemTaxRateCalculator as ProductItemTaxRateCalculatorWithMultipleShipmentTaxRate;
use Pyz\Zed\TaxProductConnector\Business\Model\ProductItemTaxRateCalculator;
use Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory as SprykerTaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

class TaxProductConnectorBusinessFactory extends SprykerTaxProductConnectorBusinessFactory
{
    /**
     * @deprecated Use {@link createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate()} instead.
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function createProductItemTaxRateCalculator(): CalculatorInterface
    {
        return new ProductItemTaxRateCalculator($this->getQueryContainer(), $this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate(): CalculatorInterface
    {
        return new ProductItemTaxRateCalculatorWithMultipleShipmentTaxRate(
            $this->getQueryContainer(),
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface
     */
    protected function getTaxFacade(): TaxProductConnectorToTaxInterface
    {
        return $this->getProvidedDependency(TaxProductConnectorDependencyProvider::FACADE_TAX);
    }
}
