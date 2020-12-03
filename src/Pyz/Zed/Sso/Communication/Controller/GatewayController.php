<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sso\Communication\Controller;

use Generated\Shared\Transfer\SsoAccessTokenRequestTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Pyz\Zed\Sso\Business\SsoFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenRequestTransfer $ssoAccessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCodeAction(SsoAccessTokenRequestTransfer $ssoAccessTokenRequestTransfer): SsoAccessTokenTransfer
    {
        return $this->getFacade()->getAccessTokenByCode($ssoAccessTokenRequestTransfer->getCode());
    }
}
