<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Adyen;

use SprykerEco\Shared\Adyen\AdyenConfig as SprykerEcoAdyenConfig;

class AdyenConfig extends SprykerEcoAdyenConfig
{
    public const SPLIT_TYPE_MARKETPLACE = 'MarketPlace';
    public const SPLIT_TYPE_COMMISSION = 'Commission';
}
