<?php

namespace Pyz\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Pyz\Shared\ProductApi\ProductApiConstants;
use Pyz\Zed\ProductApi\Business\Exception\UnsupportedLanguageException;
use Spryker\Shared\Kernel\Store;

class LanguageValidator implements LanguageValidatorInterface
{
    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void
    {
        $headerData = array_change_key_case($apiRequestTransfer->getHeaderData(), CASE_UPPER);
        $xSprykerLanguage = $headerData[ProductApiConstants::X_SPRYKER_LANGUAGE][0]
            ?? false;
        if (!in_array($xSprykerLanguage, Store::getInstance()->getLocales())) {
            throw new UnsupportedLanguageException();
        }
    }
}
