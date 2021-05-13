<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Customer;

use Pyz\Service\Customer\Balance\BalanceResolver;
use Pyz\Service\Customer\Balance\BalanceResolverInterface;
use Spryker\Service\Customer\CustomerServiceFactory as SprykerCustomerServiceFactory;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;

/**
 * @method \Pyz\Service\Customer\CustomerConfig getConfig()
 */
class CustomerServiceFactory extends SprykerCustomerServiceFactory
{
    /**
     * @return \Pyz\Service\Customer\Balance\BalanceResolverInterface
     */
    public function createBalanceResolver(): BalanceResolverInterface
    {
        return new BalanceResolver($this->createDecimalToIntegerConverter());
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    public function createDecimalToIntegerConverter(): DecimalToIntegerConverterInterface
    {
        return new DecimalToIntegerConverter();
    }
}
