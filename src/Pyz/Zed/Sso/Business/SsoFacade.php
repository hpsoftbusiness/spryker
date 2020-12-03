<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sso\Business;


use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\Sso\Business\SsoBusinessFactory getFactory()
 */
class SsoFacade extends AbstractFacade implements SsoFacadeInterface
{
    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer
    {
        return $this->getFactory()->createAccessToken()->getAccessTokenByCode($code);
    }
}
