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
     * @see \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN
     */
    protected const ROUTE_NAME_LOGIN = 'login';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if (!$this->isCustomerLoggedIn()) {
            $this->escapeRoute = static::ROUTE_NAME_LOGIN;

            return false;
        }

        return parent::preCondition($quoteTransfer);
    }
}
