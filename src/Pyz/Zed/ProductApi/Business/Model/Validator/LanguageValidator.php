<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Pyz\Shared\ProductApi\ProductApiConstants;
use Pyz\Zed\ProductApi\Business\Exception\UnsupportedLanguageException;
use Spryker\Shared\Kernel\Store;

class LanguageValidator implements LanguageValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Pyz\Zed\ProductApi\Business\Exception\UnsupportedLanguageException
     *
     * @return void
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
