<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Nopayment\Business;

use Pyz\Zed\Nopayment\Business\Nopayment\NopaymentMethodFilter;
use Spryker\Zed\Nopayment\Business\NopaymentBusinessFactory as SpyNopaymentBusinessFactory;

class NopaymentBusinessFactory extends SpyNopaymentBusinessFactory
{
    /**
     * @return \Spryker\Zed\Nopayment\Business\Nopayment\NopaymentMethodFilter
     */
    public function createNopaymentMethodFilter()
    {
        return new NopaymentMethodFilter($this->getConfig());
    }
}
