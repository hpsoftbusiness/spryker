<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep as SprykerCustomerStep;

class CustomerStep extends SprykerCustomerStep
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        if (!$this->isCustomerLoggedIn()) {
            $this->escapeRoute = 'login';

            return false;
        }

        return parent::preCondition($quoteTransfer);
    }
}
