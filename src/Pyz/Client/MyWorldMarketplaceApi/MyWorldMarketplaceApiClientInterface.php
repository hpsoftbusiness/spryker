<?php

namespace Pyz\Client\MyWorldMarketplaceApi;

use Generated\Shared\Transfer\AccessTokenTransfer;

interface MyWorldMarketplaceApiClientInterface
{
    public function getAccessToken(): AccessTokenTransfer;
}
