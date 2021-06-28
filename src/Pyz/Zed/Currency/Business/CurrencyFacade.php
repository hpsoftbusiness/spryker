<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Currency\Business;

use Spryker\Zed\Currency\Business\CurrencyFacade as SprykerCurrencyFacade;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeInterface;

class CurrencyFacade extends SprykerCurrencyFacade implements
    CurrencyFacadeInterface,
    PriceProductToCurrencyFacadeInterface
{
}
