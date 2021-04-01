<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Event\Business;

use Spryker\Zed\Event\Business\EventFacade as SprykerEventFacade;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface;

class EventFacade extends SprykerEventFacade implements ProductToEventInterface
{
}
