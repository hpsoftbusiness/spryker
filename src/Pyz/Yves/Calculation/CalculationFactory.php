<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Calculation;

use Pyz\Client\Quote\QuoteClientInterface;
use Pyz\Yves\Calculation\Mapper\CalculationResponseMapper;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Money\MoneyClientInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class CalculationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient(): CartClientInterface
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\Money\MoneyClientInterface
     */
    public function getMoneyClient(): MoneyClientInterface
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \Pyz\Client\Quote\QuoteClientInterface
     */
    public function getQuoteClient(): QuoteClientInterface
    {
        return $this->getProvidedDependency(CalculationDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Pyz\Yves\Calculation\Mapper\CalculationResponseMapper
     */
    public function createCalculationResponseMapper(): CalculationResponseMapper
    {
        return new CalculationResponseMapper(
            $this->getMoneyClient()
        );
    }
}
