<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\MyWorldPaymentApi;

use Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer;

interface MyWorldPaymentApiServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\AuthorizationHeaderRequestTransfer $authorizationHeaderRequest
     *
     * @return string
     */
    public function getAuthorizationHeader(AuthorizationHeaderRequestTransfer $authorizationHeaderRequest): string;
}
