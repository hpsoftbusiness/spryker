<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business;

use Pyz\Zed\Adyen\Business\Expander\OrderExpander;
use Pyz\Zed\Adyen\Business\Expander\OrderExpanderInterface;
use SprykerEco\Zed\Adyen\Business\AdyenBusinessFactory as SprykerEcoAdyenBusinessFactory;

class AdyenBusinessFactory extends SprykerEcoAdyenBusinessFactory
{
    /**
     * @return \Pyz\Zed\Adyen\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->getRepository());
    }
}
