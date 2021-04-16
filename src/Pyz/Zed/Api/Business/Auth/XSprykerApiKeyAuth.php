<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Pyz\Zed\Api\Business\Exception\XSprykerApiKeyException
     *
     * @return void
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
