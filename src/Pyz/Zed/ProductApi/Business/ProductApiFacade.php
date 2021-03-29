<?php

namespace Pyz\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\ProductApi\Business\ProductApiBusinessFactory getFactory()
 */
class ProductApiFacade extends AbstractFacade implements ProductApiFacadeInterface
{
    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @return ApiItemTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer
    {
        return $this->getFactory()
            ->createProductApi()
            ->find($apiRequestTransfer);
    }

    /**
     * @param string $authType
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFactory()
            ->getApiFacade()
            ->checkAuth($authType, $apiRequestTransfer);
    }

    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFactory()
            ->createLanguageValidator()
            ->validateLanguage($apiRequestTransfer);
    }
}
