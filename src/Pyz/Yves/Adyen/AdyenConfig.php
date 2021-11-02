<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen;

use SprykerEco\Yves\Adyen\AdyenConfig as SprykerAdyenConfig;

/**
 * @method \Pyz\Shared\Adyen\AdyenConfig getSharedConfig()
 */
class AdyenConfig extends SprykerAdyenConfig
{
    /**
     * @return string
     */
    public function getAdyenCreditCardName(): string
    {
        return $this->getSharedConfig()::ADYEN_CREDIT_CARD;
    }
}
