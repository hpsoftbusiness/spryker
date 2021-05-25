<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Shared\Sales\Helper;

use SprykerTest\Zed\Sales\Helper\BusinessHelper;

class SalesOrderDataHelper extends BusinessHelper
{
    public const DEFAULT_OMS_PROCESS_NAME = 'DummyPrepayment01';
    public const DEFAULT_ITEM_STATE = 'new';
}
