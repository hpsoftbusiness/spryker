<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductApi\Business\ProductApiBusinessFactory getFactory()
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
class ProductApiFacade extends AbstractFacade implements ProductApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer
    {
        return $this->getFactory()
            ->createProductApi()
            ->find($apiRequestTransfer);
    }

    /**
     * @param string $authType
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFactory()
            ->getApiFacade()
            ->checkAuth($authType, $apiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFactory()
            ->createLanguageValidator()
            ->validateLanguage($apiRequestTransfer);
    }
}
