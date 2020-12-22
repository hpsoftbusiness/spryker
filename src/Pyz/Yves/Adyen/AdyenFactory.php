<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Adyen;

use Pyz\Yves\Adyen\Handler\AdyenPaymentHandler;
use SprykerEco\Yves\Adyen\AdyenFactory as SprykerEcoAdyenFactory;
use SprykerEco\Yves\Adyen\Handler\AdyenPaymentHandlerInterface;

/**
 * @method \SprykerEco\Yves\Adyen\AdyenConfig getConfig()
 */
class AdyenFactory extends SprykerEcoAdyenFactory
{
    /**
     * @return \SprykerEco\Yves\Adyen\Handler\AdyenPaymentHandlerInterface
     */
    public function createAdyenPaymentHandler(): AdyenPaymentHandlerInterface
    {
        return new AdyenPaymentHandler(
            $this->getAdyenService(),
            $this->getAdyenPaymentPlugins()
        );
    }
}
