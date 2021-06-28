<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct\Communication;

use Pyz\Zed\PriceProduct\PriceProductDependencyProvider;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Communication\PriceProductCommunicationFactory as SprykerPriceProductCommunicationFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class PriceProductCommunicationFactory extends SprykerPriceProductCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    public function createDecimalToIntegerConverter(): DecimalToIntegerConverterInterface
    {
        return new DecimalToIntegerConverter();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    public function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_CURRENCY);
    }
}
