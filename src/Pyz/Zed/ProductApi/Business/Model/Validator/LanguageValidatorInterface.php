<?php

namespace Pyz\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;

interface LanguageValidatorInterface
{
    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void;
}
