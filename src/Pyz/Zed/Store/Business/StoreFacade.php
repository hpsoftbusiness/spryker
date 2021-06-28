<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Store\Business;

use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacade as SprykerStoreFacade;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class StoreFacade extends SprykerStoreFacade implements
    StoreFacadeInterface,
    PriceProductToStoreFacadeInterface
{
}
