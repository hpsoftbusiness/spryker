<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
interface LanguageValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void;
}
