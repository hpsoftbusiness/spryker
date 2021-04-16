<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface ProductApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer;

    /**
     * @retrun void
     *
     * @param string $authType
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void;
}
