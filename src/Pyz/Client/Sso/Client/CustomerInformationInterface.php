<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;

interface CustomerInformationInterface
{
    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerInformationBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer): CustomerTransfer;
}
