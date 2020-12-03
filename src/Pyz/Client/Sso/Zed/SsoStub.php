<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Zed;

use Generated\Shared\Transfer\SsoAccessTokenRequestTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;
use Spryker\Shared\Log\LoggerTrait;

class SsoStub extends ZedRequestStub implements SsoStubInterface
{
    use LoggerTrait;
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer
    {
        $this->getLogger()->error(__CLASS__ . ' SSO: CALLING ZED');
        /** @var \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer */
        $ssoAccessTokenTransfer = $this->zedStub->call('/sso/gateway/get-access-token-by-code', (new SsoAccessTokenRequestTransfer())->setCode($code));
        $this->getLogger()->error(__CLASS__ . ' SSO: GOT RESPONSE FROM ZED');
        return $ssoAccessTokenTransfer;
    }
}
