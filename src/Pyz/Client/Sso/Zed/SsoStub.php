<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Zed;

use Generated\Shared\Transfer\SsoAccessTokenRequestTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class SsoStub extends ZedRequestStub implements SsoStubInterface
{
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer
    {
        /** @var \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer */
        $ssoAccessTokenTransfer = $this->zedStub->call('/sso/gateway/get-access-token-by-code', (new SsoAccessTokenRequestTransfer())->setCode($code));

        return $ssoAccessTokenTransfer;
    }
}
