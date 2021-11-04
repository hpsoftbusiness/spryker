<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPageNO;

use Pyz\Yves\CheckoutPage\CheckoutPageFactory as PyzCheckoutPageFactory;
use Pyz\Yves\CheckoutPage\Form\FormFactory as PyzFormFactory;
use Pyz\Yves\CheckoutPageNO\Form\FormFactory;

class CheckoutPageFactory extends PyzCheckoutPageFactory
{
    /**
     * @return \Pyz\Yves\CheckoutPage\Form\FormFactory
     */
    public function createCheckoutFormFactory(): PyzFormFactory
    {
        return new FormFactory();
    }
}
