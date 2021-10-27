<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Controller\AccessTokenController as SprykerAccessTokenController;

class AccessTokenController extends SprykerAccessTokenController
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getLoggedInCustomerTransfer()
    {
        return parent::getLoggedInCustomerTransfer() ?? new CustomerTransfer();
    }
}