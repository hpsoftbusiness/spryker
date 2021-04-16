<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Api\Business;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\ApiFacade as SprykerApiFacade;

/**
 * @method \Pyz\Zed\Api\Business\ApiBusinessFactory getFactory()
 */
class ApiFacade extends SprykerApiFacade implements ApiFacadeInterface
{
    /**
     * @param string $authType
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFactory()
            ->createAuth($authType)
            ->checkAuth($apiRequestTransfer);
    }
}
