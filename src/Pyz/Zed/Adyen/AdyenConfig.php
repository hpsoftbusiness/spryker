<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen;

use Pyz\Shared\Adyen\AdyenConstants;
use SprykerEco\Zed\Adyen\AdyenConfig as SprykerEcoAdyenConfig;

class AdyenConfig extends SprykerEcoAdyenConfig
{
    /**
     * @return string
     */
    public function getSplitAccount(): string
    {
        return $this->get(AdyenConstants::SPLIT_ACCOUNT);
    }
}
