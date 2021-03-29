<?php

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
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @throws Exception\UnsupportedAuthTypeException
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFactory()
            ->createAuth($authType)
            ->checkAuth($apiRequestTransfer);
    }
}
