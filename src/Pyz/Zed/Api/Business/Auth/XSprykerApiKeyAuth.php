<?php

namespace Pyz\Zed\Api\Business\Auth;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Pyz\Shared\Api\ApiConstants;
use Pyz\Zed\Api\Business\Exception\XSprykerApiKeyException;
use Spryker\Shared\Config\Config;

class XSprykerApiKeyAuth implements AuthInterface
{
    /**
     * Checks whether endpoint can be reached
     *
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @throws XSprykerApiKeyException
     */
    public function checkAuth(ApiRequestTransfer $apiRequestTransfer): void
    {
        $headerData = array_change_key_case($apiRequestTransfer->getHeaderData(), CASE_UPPER);
        $xSprykerApiKey = $headerData[ApiConstants::X_SPRYKER_API_KEY][0]
            ?? false;
        if ($xSprykerApiKey !== Config::get(ApiConstants::X_SPRYKER_API_KEY)) {
            throw new XSprykerApiKeyException();
        }
    }
}
